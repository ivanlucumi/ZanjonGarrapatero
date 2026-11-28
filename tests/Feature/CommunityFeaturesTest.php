<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Mail\InstitutionalBroadcastMail;
use App\Models\Activity;
use App\Models\ContactMessage;
use App\Models\Publication;
use App\Models\SentEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CommunityFeaturesTest extends TestCase
{
    use RefreshDatabase;

    public function test_publications_and_activities_respect_active_date_windows(): void
    {
        $admin = User::factory()->create(['role' => UserRole::SuperAdmin]);

        // Publicación activa (dentro del rango)
        $activePub = Publication::create([
            'title' => 'Noticia Vigente Hoy',
            'category' => 'Comunicado',
            'content' => 'Contenido vigente.',
            'is_visible' => true,
            'active_from' => now()->subDay(),
            'active_until' => now()->addDay(),
            'author_id' => $admin->id,
        ]);

        // Publicación futura (aún no inicia)
        $futurePub = Publication::create([
            'title' => 'Noticia Programada Futuro',
            'category' => 'Comunicado',
            'content' => 'Aún no visible.',
            'is_visible' => true,
            'active_from' => now()->addDays(2),
            'active_until' => now()->addDays(5),
            'author_id' => $admin->id,
        ]);

        // Publicación expirada
        $expiredPub = Publication::create([
            'title' => 'Noticia Expirada Ayer',
            'category' => 'Comunicado',
            'content' => 'Ya venció.',
            'is_visible' => true,
            'active_from' => now()->subDays(5),
            'active_until' => now()->subDay(),
            'author_id' => $admin->id,
        ]);

        $visiblePubs = Publication::query()->visible()->pluck('title')->toArray();
        $this->assertContains($activePub->title, $visiblePubs);
        $this->assertNotContains($futurePub->title, $visiblePubs);
        $this->assertNotContains($expiredPub->title, $visiblePubs);

        // Eventos con fechas activas
        $activeAct = Activity::create([
            'title' => 'Asamblea Vigente',
            'category' => 'Asamblea',
            'description' => 'Vigente',
            'event_date' => now()->addDays(2),
            'active_from' => now()->subHour(),
            'active_until' => now()->addDays(3),
            'status' => 'programada',
            'is_visible' => true,
            'created_by' => $admin->id,
        ]);

        $expiredAct = Activity::create([
            'title' => 'Asamblea Expirada',
            'category' => 'Asamblea',
            'description' => 'Expirada',
            'event_date' => now()->subDays(2),
            'active_from' => now()->subDays(5),
            'active_until' => now()->subHour(),
            'status' => 'finalizada',
            'is_visible' => true,
            'created_by' => $admin->id,
        ]);

        $visibleActs = Activity::query()->visible()->pluck('title')->toArray();
        $this->assertContains($activeAct->title, $visibleActs);
        $this->assertNotContains($expiredAct->title, $visibleActs);
    }

    public function test_actividades_page_renders_full_calendar_with_events(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        Activity::create([
            'title' => 'Minga Territorial FullCalendar',
            'category' => 'Minga',
            'description' => 'Evento visible en calendario',
            'location' => 'El Palmar',
            'event_date' => now()->addDays(4),
            'status' => 'programada',
            'is_visible' => true,
            'created_by' => $admin->id,
        ]);

        $response = $this->get(route('actividades'));

        $response->assertStatus(200);
        $response->assertSee('communityFullCalendar');
        $response->assertSee('fullcalendar', false);
        $response->assertSee('Minga Territorial FullCalendar');
    }

    public function test_admin_receives_unread_notifications_for_pending_requests(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);
        $comunero = User::factory()->create(['role' => UserRole::Usuario]);

        ContactMessage::create([
            'name' => 'María Solicitante',
            'email' => 'maria@ejemplo.com',
            'territory' => 'Santa Lucía',
            'message' => 'Solicito certificado territorial urgente.',
            'status' => 'pendiente',
        ]);

        $this->actingAs($comunero)
            ->getJson(route('admin.notifications.unread'))
            ->assertStatus(403);

        $response = $this->actingAs($admin)
            ->getJson(route('admin.notifications.unread'));

        $response->assertStatus(200)
            ->assertJsonPath('unread_count', 1)
            ->assertJsonFragment(['name' => 'María Solicitante']);
    }

    public function test_admin_can_send_individual_by_role_and_massive_emails(): void
    {
        Mail::fake();

        $admin = User::factory()->create(['role' => UserRole::SuperAdmin, 'email' => 'jefe@zanjon.gov.co']);
        $user1 = User::factory()->create(['role' => UserRole::Usuario, 'email' => 'comunero1@zanjon.gov.co']);
        $user2 = User::factory()->create(['role' => UserRole::Usuario, 'email' => 'comunero2@zanjon.gov.co']);
        $staff = User::factory()->create(['role' => UserRole::Admin, 'email' => 'censo@zanjon.gov.co']);

        // 1. Individual
        $this->actingAs($admin)->post(route('admin.emails.store'), [
            'send_type' => 'individual',
            'recipient_user_id' => $user1->id,
            'subject' => 'Citación Individual',
            'body' => 'Por favor acercarse a la oficina.',
        ])->assertRedirect(route('admin.emails.index'));

        Mail::assertSent(InstitutionalBroadcastMail::class, 1);
        $this->assertDatabaseHas('sent_emails', [
            'send_type' => 'individual',
            'recipients_count' => 1,
            'subject' => 'Citación Individual',
        ]);

        // 2. Por Rol (Usuario = 2)
        Mail::fake();
        $this->actingAs($admin)->post(route('admin.emails.store'), [
            'send_type' => 'role',
            'target_role' => UserRole::Usuario->value,
            'subject' => 'Aviso para Comuneros',
            'body' => 'Convocatoria de censo para todos los comuneros.',
        ])->assertRedirect(route('admin.emails.index'));

        Mail::assertSent(InstitutionalBroadcastMail::class, 2);
        $this->assertDatabaseHas('sent_emails', [
            'send_type' => 'role',
            'target_role' => 'usuario',
            'recipients_count' => 2,
        ]);

        // 3. Masivo (Todos = 4 inscritos)
        Mail::fake();
        $this->actingAs($admin)->post(route('admin.emails.store'), [
            'send_type' => 'massive',
            'subject' => 'Comunicado Masivo General',
            'body' => 'Mensaje para toda la comunidad inscrita.',
        ])->assertRedirect(route('admin.emails.index'));

        Mail::assertSent(InstitutionalBroadcastMail::class, 4);
        $this->assertDatabaseHas('sent_emails', [
            'send_type' => 'massive',
            'recipients_count' => 4,
        ]);

        // 4. Vistas del Centro de Correos
        $latest = SentEmail::latest('id')->first();
        $this->actingAs($admin)->get(route('admin.emails.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.emails.create'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.emails.show', $latest->id))->assertStatus(200);
    }

    public function test_admin_can_send_to_multiple_selected_users_with_attachments_and_download_links(): void
    {
        Mail::fake();
        Storage::fake('public');

        $admin = User::factory()->create(['role' => UserRole::SuperAdmin]);
        $user1 = User::factory()->create(['role' => UserRole::Usuario]);
        $user2 = User::factory()->create(['role' => UserRole::Usuario]);

        // 1. Adjunto directo pequeño (<= límite)
        $smallFile = UploadedFile::fake()->create('acta.pdf', 50, 'application/pdf');
        $this->actingAs($admin)->post(route('admin.emails.store'), [
            'send_type' => 'individual',
            'recipient_user_ids' => [$user1->id, $user2->id],
            'subject' => 'Correo Multi-Selección con Adjunto',
            'body' => 'Adjuntamos el acta oficial.',
            'attachments' => [$smallFile],
        ])->assertRedirect(route('admin.emails.index'));

        Mail::assertSent(InstitutionalBroadcastMail::class, 2);
        Mail::assertSent(InstitutionalBroadcastMail::class, function (InstitutionalBroadcastMail $mail): bool {
            return count($mail->attachedFiles) === 1 && count($mail->downloadLinks) === 0;
        });

        // 2. Archivo que supera el límite -> convertido automáticamente a enlace de descarga
        Mail::fake();
        Config::set('mail.attachment_max_bytes', 1024); // 1 KB para forzar modo enlace
        $largeFile = UploadedFile::fake()->create('video-asamblea.mp4', 200, 'video/mp4');

        $this->actingAs($admin)->post(route('admin.emails.store'), [
            'send_type' => 'individual',
            'recipient_user_ids' => [$user1->id],
            'subject' => 'Correo con Enlace por Peso',
            'body' => 'Descarga el archivo pesado desde el enlace.',
            'attachments' => [$largeFile],
        ])->assertRedirect(route('admin.emails.index'));

        Mail::assertSent(InstitutionalBroadcastMail::class, function (InstitutionalBroadcastMail $mail): bool {
            return count($mail->attachedFiles) === 0
                && count($mail->downloadLinks) === 1
                && str_contains($mail->downloadLinks[0]['name'], 'video-asamblea.mp4');
        });
    }

    public function test_database_backup_command_generates_sql_file(): void
    {
        Storage::fake('local');

        $exitCode = Artisan::call('db:backup');
        $this->assertSame(0, $exitCode);

        $files = Storage::disk('local')->files('backups');
        $this->assertNotEmpty($files);
        $this->assertStringEndsWith('.sql', $files[0]);
    }

    public function test_security_headers_are_applied_and_domain_restriction_is_removed(): void
    {
        $response = $this->get('http://consejozanjondegarrapatero.org/');
        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Cualquier otro dominio o subdominio también responde sin bloqueo de dominio
        $this->get('http://cualquier-otro-dominio.com/')->assertStatus(200);
    }

    public function test_seo_favicon_opengraph_sitemap_and_topbar_removal(): void
    {
        $home = $this->get(route('index'));
        $home->assertStatus(200);
        $home->assertDontSee('Un territorio, muchas voces, una comunidad');
        $home->assertSee('brand-mark-lg');
        $home->assertSee('rel="icon"', false);
        $home->assertSee('og:image', false);
        $home->assertSee(asset('logo/logo.webp'), false);
        $home->assertSee('application/ld+json', false);

        $sitemap = $this->get(route('sitemap'));
        $sitemap->assertStatus(200);
        $sitemap->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $sitemap->assertSee(route('nosotros'));
        $sitemap->assertSee(route('actividades'));
    }

    public function test_login_renders_google_recaptcha_and_validates_when_enabled(): void
    {
        $loginPage = $this->get(route('login'));
        $loginPage->assertStatus(200);
        $loginPage->assertSee('g-recaptcha');
        $loginPage->assertSee('https://www.google.com/recaptcha/api.js?hl=es', false);

        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
            'role' => UserRole::SuperAdmin,
        ]);

        Config::set('services.recaptcha.enabled', true);
        Config::set('services.recaptcha.secret_key', 'test-secret-key');

        // Sin token g-recaptcha-response debe fallar validación
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ])->assertSessionHasErrors('g-recaptcha-response');

        // Con token válido verificado por Google API debe iniciar sesión
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true], 200),
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret123',
            'g-recaptcha-response' => 'valid-token-demo',
        ])->assertRedirect(route('admin.dashboard'));
    }
}
