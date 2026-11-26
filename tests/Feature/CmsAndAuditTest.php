<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Activity;
use App\Models\BoardMember;
use App\Models\CarouselSlide;
use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\MediaItem;
use App\Models\Publication;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsAndAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_publications_list(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $pub = Publication::create([
            'title' => 'Noticia Comunitaria Test',
            'slug' => 'noticia-comunitaria-test',
            'category' => 'Noticia',
            'content' => 'Contenido de prueba para el territorio.',
            'author_id' => $admin->id,
            'is_visible' => true,
            'published_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/publications');

        $response->assertStatus(200);
        $response->assertSee('Noticia Comunitaria Test');
        $response->assertSee('Visible al Público');
    }

    public function test_admin_can_create_publication_and_audit_log_is_recorded(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
            'name' => 'Coordinadora de Prensa',
        ]);

        $payload = [
            'title' => 'Convocatoria a Minga de Limpieza',
            'category' => 'Comunicado',
            'summary' => 'Resumen de la minga comunitaria veredal.',
            'content' => 'Se convoca a todos los comuneros a participar activamente en la minga.',
            'image_url' => 'https://example.com/imagen.jpg',
            'is_visible' => '1',
        ];

        $response = $this->actingAs($admin)->post('/admin/publications', $payload);

        $response->assertRedirect('/admin/publications');
        $this->assertDatabaseHas('publications', [
            'title' => 'Convocatoria a Minga de Limpieza',
            'category' => 'Comunicado',
            'is_visible' => 1,
            'author_id' => $admin->id,
        ]);

        // Verify Audit Log was recorded
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'crear_publicacion',
            'user_id' => $admin->id,
            'user_name' => 'Coordinadora de Prensa',
            'entity_type' => 'Publication',
        ]);
    }

    public function test_admin_can_toggle_publication_visibility(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $pub = Publication::create([
            'title' => 'Publicación Visibilidad Test',
            'slug' => 'publicacion-visibilidad-test',
            'category' => 'Noticia',
            'content' => 'Contenido de visibilidad.',
            'author_id' => $admin->id,
            'is_visible' => true,
        ]);

        $response = $this->actingAs($admin)->patch("/admin/publications/{$pub->id}/toggle-visibility");

        $response->assertRedirect();
        $this->assertDatabaseHas('publications', [
            'id' => $pub->id,
            'is_visible' => false,
        ]);

        // Audit log for visibility toggle
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'cambio_visibilidad_publicacion',
            'entity_id' => $pub->id,
        ]);
    }

    public function test_publication_is_soft_deleted_not_permanently_destroyed_and_can_be_restored(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $pub = Publication::create([
            'title' => 'Artículo para Inactivar',
            'slug' => 'articulo-para-inactivar',
            'category' => 'Asamblea',
            'content' => 'Texto histórico que debe resguardarse.',
            'author_id' => $admin->id,
            'is_visible' => true,
        ]);

        // Inactivate (Soft delete via destroy)
        $deleteResponse = $this->actingAs($admin)->delete("/admin/publications/{$pub->id}");
        $deleteResponse->assertRedirect();

        // Must still exist in database with deleted_at timestamp
        $this->assertSoftDeleted('publications', [
            'id' => $pub->id,
        ]);

        // Audit log recorded
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'inactivar_publicacion',
            'entity_id' => $pub->id,
        ]);

        // Restore soft-deleted publication
        $restoreResponse = $this->actingAs($admin)->patch("/admin/publications/{$pub->id}/restore");
        $restoreResponse->assertRedirect();

        $this->assertNotSoftDeleted('publications', [
            'id' => $pub->id,
        ]);

        // Audit log for restoration
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'restaurar_publicacion',
            'entity_id' => $pub->id,
        ]);
    }

    public function test_activity_crud_and_soft_delete(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $storeResponse = $this->actingAs($admin)->post('/admin/activities', [
            'title' => 'Taller de Tambores Afro',
            'category' => 'Cultura',
            'description' => 'Taller de percusión para jóvenes comuneros.',
            'location' => 'Salón Veredal El Palmar',
            'event_date' => now()->addDays(5)->format('Y-m-d\TH:i'),
            'status' => 'programada',
            'is_visible' => '1',
        ]);

        $storeResponse->assertRedirect('/admin/activities');
        $this->assertDatabaseHas('activities', [
            'title' => 'Taller de Tambores Afro',
            'status' => 'programada',
        ]);

        $activity = Activity::where('title', 'Taller de Tambores Afro')->firstOrFail();

        // Soft delete activity
        $deleteResponse = $this->actingAs($admin)->delete("/admin/activities/{$activity->id}");
        $deleteResponse->assertRedirect();

        $this->assertSoftDeleted('activities', [
            'id' => $activity->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'inactivar_actividad',
            'entity_id' => $activity->id,
        ]);
    }

    public function test_media_item_crud_and_soft_delete(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $storeResponse = $this->actingAs($admin)->post('/admin/media', [
            'title' => 'Río Zanjón y Paisaje',
            'type' => 'image',
            'category' => 'Territorio',
            'caption' => 'Fotografía del afluente principal.',
            'file_url' => 'https://example.com/rio.jpg',
            'is_visible' => '1',
            'is_featured' => '1',
        ]);

        $storeResponse->assertRedirect('/admin/media');
        $this->assertDatabaseHas('media_items', [
            'title' => 'Río Zanjón y Paisaje',
            'type' => 'image',
            'is_featured' => 1,
        ]);

        $item = MediaItem::where('title', 'Río Zanjón y Paisaje')->firstOrFail();

        // Soft delete media item
        $deleteResponse = $this->actingAs($admin)->delete("/admin/media/{$item->id}");
        $deleteResponse->assertRedirect();

        $this->assertSoftDeleted('media_items', [
            'id' => $item->id,
        ]);
    }

    public function test_user_login_creates_login_log_and_audit_log_entry(): void
    {
        $user = User::factory()->create([
            'email' => 'comunero.audit@zanjon.gov.co',
            'password' => bcrypt('secret123'),
            'role' => UserRole::Usuario,
            'name' => 'Comunero Test Log',
        ]);

        $response = $this->post('/login', [
            'email' => 'comunero.audit@zanjon.gov.co',
            'password' => 'secret123',
        ], [
            'REMOTE_ADDR' => '192.168.1.100',
            'HTTP_USER_AGENT' => 'TestBrowser/1.0 (Windows)',
        ]);

        $response->assertRedirect(route('admin.surveys.index'));

        // Check login log was recorded
        $this->assertDatabaseHas('login_logs', [
            'user_id' => $user->id,
            'user_name' => 'Comunero Test Log',
            'user_email' => 'comunero.audit@zanjon.gov.co',
        ]);

        // Check audit log was recorded
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'inicio_sesion',
            'user_id' => $user->id,
        ]);
    }

    public function test_comunero_cannot_access_cms_management_or_audit(): void
    {
        $comunero = User::factory()->create(['role' => UserRole::Usuario]);

        // Forbidden from CMS creation
        $cmsResponse = $this->actingAs($comunero)->get('/admin/publications/create');
        $cmsResponse->assertStatus(403);

        // Forbidden from Audit trails
        $auditResponse = $this->actingAs($comunero)->get('/admin/audit');
        $auditResponse->assertStatus(403);

        // Forbidden from Login logs
        $loginsResponse = $this->actingAs($comunero)->get('/admin/audit/logins');
        $loginsResponse->assertStatus(403);
    }

    public function test_admin_cannot_access_audit_trails_exclusive_to_super_admin(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        // Admin can access CMS
        $cmsResponse = $this->actingAs($admin)->get('/admin/publications');
        $cmsResponse->assertStatus(200);

        // Admin is forbidden from audit trails (super_admin exclusive)
        $auditResponse = $this->actingAs($admin)->get('/admin/audit');
        $auditResponse->assertStatus(403);
    }

    public function test_super_admin_can_access_audit_trails_and_login_history(): void
    {
        $superAdmin = User::factory()->create(['role' => UserRole::SuperAdmin]);

        // Super Admin views audit logs
        $auditResponse = $this->actingAs($superAdmin)->get('/admin/audit');
        $auditResponse->assertStatus(200);
        $auditResponse->assertSee('Bitácora de Auditoría');

        // Super Admin views login history
        $loginsResponse = $this->actingAs($superAdmin)->get('/admin/audit/logins');
        $loginsResponse->assertStatus(200);
        $loginsResponse->assertSee('Control de Acceso e Inicios de Sesión');
    }

    public function test_public_actualidad_page_only_shows_visible_and_non_trashed_publications(): void
    {
        $author = User::factory()->create(['role' => UserRole::Admin]);

        $visiblePub = Publication::create([
            'title' => 'Noticia Visible Comunitaria',
            'slug' => 'noticia-visible-comunitaria',
            'category' => 'Noticia',
            'content' => 'Esta noticia debe aparecer en el portal público.',
            'author_id' => $author->id,
            'is_visible' => true,
            'published_at' => now(),
        ]);

        $hiddenPub = Publication::create([
            'title' => 'Noticia Oculta Privada',
            'slug' => 'noticia-oculta-privada',
            'category' => 'Comunicado',
            'content' => 'Esta noticia no debe aparecer en el portal.',
            'author_id' => $author->id,
            'is_visible' => false,
            'published_at' => now(),
        ]);

        $trashedPub = Publication::create([
            'title' => 'Noticia Inactivada Papelera',
            'slug' => 'noticia-inactivada-papelera',
            'category' => 'Territorio',
            'content' => 'Esta noticia fue inactivada y no debe verse.',
            'author_id' => $author->id,
            'is_visible' => true,
            'published_at' => now(),
        ]);
        $trashedPub->delete(); // Soft delete

        $response = $this->get('/actualidad');
        $response->assertStatus(200);
        $response->assertSee('Noticia Visible Comunitaria');
        $response->assertDontSee('Noticia Oculta Privada');
        $response->assertDontSee('Noticia Inactivada Papelera');
    }

    public function test_admin_can_manage_carousel_slides_and_soft_delete(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin, 'name' => 'Admin Carrusel']);

        // 1. Create slide
        $payload = [
            'title' => 'Slide Test Territorio',
            'subtitle' => 'Subtítulo del slide territorial',
            'badge_text' => 'Consejo Mayor',
            'type' => 'image',
            'media_url' => 'https://example.com/slide.jpg',
            'projection_title' => 'Proyección Test',
            'primary_button_text' => 'Conocer Más',
            'primary_button_url' => '/territorio',
            'sort_order' => 1,
            'is_visible' => '1',
        ];

        $createResponse = $this->actingAs($admin)->post('/admin/carousel', $payload);
        $createResponse->assertRedirect('/admin/carousel');

        $this->assertDatabaseHas('carousel_slides', [
            'title' => 'Slide Test Territorio',
            'type' => 'image',
            'is_visible' => 1,
        ]);

        $slide = CarouselSlide::where('title', 'Slide Test Territorio')->firstOrFail();

        // 2. Toggle visibility
        $toggleResponse = $this->actingAs($admin)->patch("/admin/carousel/{$slide->id}/toggle-visibility");
        $toggleResponse->assertRedirect();
        $this->assertDatabaseHas('carousel_slides', [
            'id' => $slide->id,
            'is_visible' => false,
        ]);

        // 3. Soft delete (Inactivar)
        $deleteResponse = $this->actingAs($admin)->delete("/admin/carousel/{$slide->id}");
        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('carousel_slides', ['id' => $slide->id]);

        // 4. Restore
        $restoreResponse = $this->actingAs($admin)->patch("/admin/carousel/{$slide->id}/restore");
        $restoreResponse->assertRedirect();
        $this->assertNotSoftDeleted('carousel_slides', ['id' => $slide->id]);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'crear_slide_carrusel',
            'user_id' => $admin->id,
            'entity_type' => 'CarouselSlide',
        ]);
    }

    public function test_contact_form_stores_message_in_database_and_records_audit(): void
    {
        $payload = [
            'name' => 'Comunera Faustina Balanta',
            'email' => 'faustina@example.com',
            'phone' => '3120001122',
            'territory' => 'Santa Lucía',
            'message' => 'Solicitud de inclusión en la próxima minga ambiental comunitaria.',
        ];

        $response = $this->post('/contacto', $payload);
        $response->assertRedirect();

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Comunera Faustina Balanta',
            'email' => 'faustina@example.com',
            'territory' => 'Santa Lucía',
            'status' => 'pendiente',
        ]);

        // Audit log for public contact submission
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'radicar_contacto_web',
            'entity_type' => 'ContactMessage',
        ]);
    }

    public function test_admin_can_update_contact_message_status_and_notes(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin, 'name' => 'Secretaría General']);

        $message = ContactMessage::create([
            'name' => 'Juan Mina',
            'email' => 'juan@example.com',
            'phone' => '3119998877',
            'territory' => 'El Palmar',
            'message' => 'Pregunta sobre certificado territorial.',
            'status' => 'pendiente',
        ]);

        $response = $this->actingAs($admin)->put("/admin/messages/{$message->id}", [
            'status' => 'en_tramite',
            'admin_notes' => 'Se verificó en libro de actas veredal.',
        ]);

        $response->assertRedirect("/admin/messages/{$message->id}");
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'en_tramite',
            'admin_notes' => 'Se verificó en libro de actas veredal.',
            'attended_by' => $admin->id,
        ]);

        // Soft delete message
        $deleteResponse = $this->actingAs($admin)->delete("/admin/messages/{$message->id}");
        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('contact_messages', ['id' => $message->id]);

        // Restore message
        $restoreResponse = $this->actingAs($admin)->patch("/admin/messages/{$message->id}/restore");
        $restoreResponse->assertRedirect();
        $this->assertNotSoftDeleted('contact_messages', ['id' => $message->id]);
    }

    public function test_admin_can_manage_territories_and_soft_delete(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin, 'name' => 'Admin Territorio']);

        $payload = [
            'name' => 'Vereda San Antonio Test',
            'sector_badge' => 'Sector Norte',
            'description' => 'Descripción amplia del sector norte y sus afluentes.',
            'estimated_families' => 75,
            'watersheds' => 'Microcuenca Quebrada Honda',
            'main_activities' => 'Piscicultura y plátano',
            'sort_order' => 5,
            'is_visible' => '1',
        ];

        $createResponse = $this->actingAs($admin)->post('/admin/territories', $payload);
        $createResponse->assertRedirect('/admin/territories');

        $this->assertDatabaseHas('territories', [
            'name' => 'Vereda San Antonio Test',
            'slug' => 'vereda-san-antonio-test',
            'estimated_families' => 75,
        ]);

        $territory = Territory::where('slug', 'vereda-san-antonio-test')->firstOrFail();

        // Toggle visibility
        $toggleResponse = $this->actingAs($admin)->patch("/admin/territories/{$territory->id}/toggle-visibility");
        $toggleResponse->assertRedirect();
        $this->assertDatabaseHas('territories', [
            'id' => $territory->id,
            'is_visible' => false,
        ]);

        // Soft delete
        $deleteResponse = $this->actingAs($admin)->delete("/admin/territories/{$territory->id}");
        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('territories', ['id' => $territory->id]);

        // Restore
        $restoreResponse = $this->actingAs($admin)->patch("/admin/territories/{$territory->id}/restore");
        $restoreResponse->assertRedirect();
        $this->assertNotSoftDeleted('territories', ['id' => $territory->id]);
    }

    public function test_admin_can_manage_board_members_and_soft_delete(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin, 'name' => 'Admin Directiva']);

        $payload = [
            'name' => 'Cipriano Balanta',
            'role_title' => 'Secretario General del Consejo',
            'committee' => 'Junta de Gobierno',
            'vereda' => 'San Antonio',
            'bio' => 'Historiador comunitario y custodio del archivo documental.',
            'phone' => '3125556677',
            'email' => 'secretaria@zanjon.gov.co',
            'sort_order' => 1,
            'is_visible' => '1',
        ];

        $createResponse = $this->actingAs($admin)->post('/admin/board', $payload);
        $createResponse->assertRedirect('/admin/board');

        $this->assertDatabaseHas('board_members', [
            'name' => 'Cipriano Balanta',
            'role_title' => 'Secretario General del Consejo',
        ]);

        $member = BoardMember::where('name', 'Cipriano Balanta')->firstOrFail();

        // Toggle visibility
        $toggleResponse = $this->actingAs($admin)->patch("/admin/board/{$member->id}/toggle-visibility");
        $toggleResponse->assertRedirect();
        $this->assertDatabaseHas('board_members', [
            'id' => $member->id,
            'is_visible' => false,
        ]);

        // Soft delete
        $deleteResponse = $this->actingAs($admin)->delete("/admin/board/{$member->id}");
        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('board_members', ['id' => $member->id]);

        // Restore
        $restoreResponse = $this->actingAs($admin)->patch("/admin/board/{$member->id}/restore");
        $restoreResponse->assertRedirect();
        $this->assertNotSoftDeleted('board_members', ['id' => $member->id]);
    }

    public function test_public_pages_render_dynamic_cms_records(): void
    {
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        CarouselSlide::create([
            'title' => 'Slide Panorámico Dinámico',
            'type' => 'image',
            'is_visible' => true,
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        Territory::create([
            'name' => 'Vereda Las Palmas Test',
            'slug' => 'vereda-las-palmas-test',
            'sector_badge' => 'Sector Verde',
            'description' => 'Descripción de la vereda dinámica.',
            'estimated_families' => 45,
            'is_visible' => true,
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        BoardMember::create([
            'name' => 'Matilde Mina Balanta',
            'role_title' => 'Consejera Mayor de Cultura',
            'committee' => 'Comité Cultural',
            'is_visible' => true,
            'sort_order' => 1,
            'created_by' => $admin->id,
        ]);

        Activity::create([
            'title' => 'Encuentro Comunitario de Saberes',
            'slug' => 'encuentro-comunitario-saberes',
            'category' => 'Cultura',
            'description' => 'Encuentro de medicina tradicional.',
            'location' => 'Kiosko Central',
            'event_date' => now()->addDays(3),
            'status' => 'programada',
            'is_visible' => true,
            'created_by' => $admin->id,
        ]);

        MediaItem::create([
            'title' => 'Video Documental de la Resistencia',
            'slug' => 'video-documental-resistencia',
            'type' => 'video',
            'category' => 'Territorio',
            'file_url' => 'https://www.youtube.com/embed/test',
            'is_visible' => true,
            'created_by' => $admin->id,
        ]);

        MediaItem::create([
            'title' => 'Fotografía del Río Garrapatero',
            'slug' => 'fotografia-rio-garrapatero',
            'type' => 'image',
            'category' => 'Paisaje',
            'file_url' => 'https://example.com/rio.jpg',
            'is_visible' => true,
            'created_by' => $admin->id,
        ]);

        // Verify Home (Carousel)
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Slide Panorámico Dinámico');

        // Verify Nosotros (Board Member)
        $nosotrosResponse = $this->get('/nosotros');
        $nosotrosResponse->assertStatus(200);
        $nosotrosResponse->assertSee('Matilde Mina Balanta');
        $nosotrosResponse->assertSee('Consejera Mayor de Cultura');

        // Verify Territorio
        $territorioResponse = $this->get('/territorio');
        $territorioResponse->assertStatus(200);
        $territorioResponse->assertSee('Vereda Las Palmas Test');

        // Verify Actividades
        $actividadesResponse = $this->get('/actividades');
        $actividadesResponse->assertStatus(200);
        $actividadesResponse->assertSee('Encuentro Comunitario de Saberes');

        // Verify Videos
        $videosResponse = $this->get('/videos');
        $videosResponse->assertStatus(200);
        $videosResponse->assertSee('Video Documental de la Resistencia');

        // Verify Galería
        $galeriaResponse = $this->get('/galeria');
        $galeriaResponse->assertStatus(200);
        $galeriaResponse->assertSee('Fotografía del Río Garrapatero');
    }

    public function test_admin_can_upload_and_optimize_image_for_carousel(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $image = UploadedFile::fake()->image('paisaje-grande.jpg', 2400, 1600);

        $payload = [
            'title' => 'Slide con Foto Optimizada',
            'type' => 'image',
            'media_file' => $image,
            'is_visible' => '1',
        ];

        $response = $this->actingAs($admin)->post('/admin/carousel', $payload);
        $response->assertRedirect('/admin/carousel');

        $slide = CarouselSlide::where('title', 'Slide con Foto Optimizada')->firstOrFail();
        $this->assertNotNull($slide->media_url);
        $this->assertStringContainsString('.webp', $slide->media_url);

        // Verify audit log
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'crear_slide_carrusel',
            'entity_type' => 'CarouselSlide',
        ]);
    }

    public function test_admin_can_upload_document_and_download_it(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => UserRole::Admin, 'name' => 'Secretaría General']);

        $pdf = UploadedFile::fake()->create('estatuto-2026.pdf', 1024, 'application/pdf');

        $payload = [
            'title' => 'Estatuto de Convivencia Territorial 2026',
            'category' => 'Normatividad y Ley 70',
            'description' => 'Documento rector aprobado en asamblea general comunitaria.',
            'document_file' => $pdf,
            'is_visible' => '1',
        ];

        $response = $this->actingAs($admin)->post('/admin/documents', $payload);
        $response->assertRedirect('/admin/documents');

        $this->assertDatabaseHas('documents', [
            'title' => 'Estatuto de Convivencia Territorial 2026',
            'category' => 'Normatividad y Ley 70',
            'file_name' => 'estatuto-2026.pdf',
            'is_visible' => 1,
        ]);

        $doc = Document::where('title', 'Estatuto de Convivencia Territorial 2026')->firstOrFail();
        Storage::disk('public')->assertExists($doc->file_path);

        // Admin download
        $downloadResponse = $this->actingAs($admin)->get("/admin/documents/{$doc->id}/download");
        $downloadResponse->assertOk();

        // Audit log verified
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'crear_documento',
            'entity_type' => 'Document',
        ]);
    }

    public function test_admin_can_update_document_and_replace_file(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $initialFile = UploadedFile::fake()->create('plan-inicial.pdf', 500, 'application/pdf');

        $this->actingAs($admin)->post('/admin/documents', [
            'title' => 'Plan Ambiental Comunitario',
            'category' => 'Planes de Manejo Ambiental',
            'document_file' => $initialFile,
            'is_visible' => '1',
        ]);

        $doc = Document::where('title', 'Plan Ambiental Comunitario')->firstOrFail();
        $oldPath = $doc->file_path;
        Storage::disk('public')->assertExists($oldPath);

        // Update with replacement file
        $newFile = UploadedFile::fake()->create('plan-ambiental-v2.pdf', 800, 'application/pdf');
        $updateResponse = $this->actingAs($admin)->put("/admin/documents/{$doc->id}", [
            'title' => 'Plan Ambiental Comunitario Actualizado',
            'category' => 'Planes de Manejo Ambiental',
            'description' => 'Versión aprobada con comités veredales.',
            'document_file' => $newFile,
            'is_visible' => '1',
        ]);

        $updateResponse->assertRedirect('/admin/documents');

        $doc->refresh();
        $this->assertEquals('Plan Ambiental Comunitario Actualizado', $doc->title);
        $this->assertEquals('plan-ambiental-v2.pdf', $doc->file_name);
        Storage::disk('public')->assertExists($doc->file_path);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_admin_can_manage_document_visibility_and_soft_delete(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $file = UploadedFile::fake()->create('acta-asamblea.pdf', 300, 'application/pdf');
        $this->actingAs($admin)->post('/admin/documents', [
            'title' => 'Acta de Asamblea Veredal',
            'category' => 'Actas de Asamblea',
            'document_file' => $file,
            'is_visible' => '1',
        ]);

        $doc = Document::where('title', 'Acta de Asamblea Veredal')->firstOrFail();

        // Toggle visibility
        $toggleResponse = $this->actingAs($admin)->patch("/admin/documents/{$doc->id}/toggle-visibility");
        $toggleResponse->assertRedirect();
        $this->assertDatabaseHas('documents', ['id' => $doc->id, 'is_visible' => false]);

        // Soft delete (inactivate)
        $deleteResponse = $this->actingAs($admin)->delete("/admin/documents/{$doc->id}");
        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('documents', ['id' => $doc->id]);

        // Restore
        $restoreResponse = $this->actingAs($admin)->patch("/admin/documents/{$doc->id}/restore");
        $restoreResponse->assertRedirect();
        $this->assertNotSoftDeleted('documents', ['id' => $doc->id]);
    }

    public function test_public_user_can_view_and_download_documents(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        $pdf = UploadedFile::fake()->create('reglamento-publico.pdf', 400, 'application/pdf');
        $this->actingAs($admin)->post('/admin/documents', [
            'title' => 'Reglamento de Guardia Cimarrona',
            'category' => 'Normatividad y Ley 70',
            'description' => 'Mandato colectivo para la guardia comunitaria.',
            'document_file' => $pdf,
            'is_visible' => '1',
        ]);

        $doc = Document::where('title', 'Reglamento de Guardia Cimarrona')->firstOrFail();

        // Public index view
        $viewResponse = $this->get('/documentos');
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Reglamento de Guardia Cimarrona');
        $viewResponse->assertSee('Normatividad y Ley 70');

        // Public download
        $downloadResponse = $this->get("/documentos/{$doc->slug}/descargar");
        $downloadResponse->assertOk();

        // Download count incremented
        $doc->refresh();
        $this->assertEquals(1, $doc->download_count);

        // Audit event logged
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'descarga_documento_web',
            'entity_type' => 'Document',
        ]);
    }

    public function test_document_upload_rejects_files_exceeding_20mb(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        // 25 MB exceeds the 20480 KB limit
        $oversizedFile = UploadedFile::fake()->create('documento-pesado.pdf', 25000, 'application/pdf');

        $response = $this->actingAs($admin)->post('/admin/documents', [
            'title' => 'Documento Demasiado Pesado',
            'category' => 'Normatividad y Ley 70',
            'document_file' => $oversizedFile,
        ]);

        $response->assertSessionHasErrors('document_file');
        $this->assertDatabaseMissing('documents', ['title' => 'Documento Demasiado Pesado']);
    }

    public function test_carousel_video_upload_rejects_files_exceeding_50mb(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => UserRole::Admin]);

        // 60 MB exceeds the 51200 KB limit
        $oversizedVideo = UploadedFile::fake()->create('video-pesado.mp4', 60000, 'video/mp4');

        $response = $this->actingAs($admin)->post('/admin/carousel', [
            'title' => 'Video Muy Pesado',
            'type' => 'video',
            'media_file' => $oversizedVideo,
        ]);

        $response->assertSessionHasErrors('media_file');
        $this->assertDatabaseMissing('carousel_slides', ['title' => 'Video Muy Pesado']);
    }
}
