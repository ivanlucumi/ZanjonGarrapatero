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
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::factory()->create([
            'role' => UserRole::SuperAdmin,
            'name' => 'Super Admin Test',
            'email' => 'admin.test@zanjon.gov.co',
        ]);
    }

    /**
     * Prueba directa del error reportado por el usuario:
     * Call to undefined method App\Models\MediaItem::isImage() en /admin/media
     */
    public function test_admin_media_index_renders_without_bad_method_call_for_images_and_videos(): void
    {
        // 1. Crear elemento tipo foto
        $imageItem = MediaItem::create([
            'title' => 'Fotografía del Territorio',
            'type' => 'image',
            'category' => 'Territorio',
            'file_url' => 'https://ejemplo.com/foto.webp',
            'is_visible' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        // 2. Crear elemento tipo video
        $videoItem = MediaItem::create([
            'title' => 'Video Tradicional Cimarrona',
            'type' => 'video',
            'category' => 'Cultura',
            'file_url' => 'https://ejemplo.com/video.mp4',
            'thumbnail_url' => 'https://ejemplo.com/miniatura.webp',
            'duration' => '3:45 min',
            'is_visible' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        // 3. Probar métodos en el modelo
        $this->assertTrue($imageItem->isImage());
        $this->assertFalse($imageItem->isVideo());
        $this->assertTrue($videoItem->isVideo());
        $this->assertFalse($videoItem->isImage());
        $this->assertNotEmpty($imageItem->displayThumbnail());
        $this->assertNotEmpty($videoItem->displayThumbnail());

        // 4. Solicitar la vista /admin/media como SuperAdmin
        $response = $this->actingAs($this->superAdmin)->get('/admin/media');

        $response->assertStatus(200);
        $response->assertSee('Fotografía del Territorio');
        $response->assertSee('Video Tradicional Cimarrona');
        $response->assertDontSee('BadMethodCallException');
    }

    /**
     * Valida que todas las vistas de administración del CMS y de Gobierno carguen exitosamente
     */
    public function test_all_admin_cms_views_render_with_populated_records(): void
    {
        // Datos de prueba para cada módulo
        $slide = CarouselSlide::create([
            'title' => 'Portada Principal de Prueba',
            'type' => 'image',
            'media_url' => 'https://ejemplo.com/portada.webp',
            'is_visible' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $board = BoardMember::create([
            'name' => 'Líder Ancestral de Prueba',
            'role_title' => 'Gobernador Mayor',
            'committee' => 'Junta de Gobierno',
            'is_visible' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $territory = Territory::create([
            'name' => 'Vereda Central de Prueba',
            'description' => 'Descripción veredal',
            'is_visible' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $pub = Publication::create([
            'title' => 'Comunicado Oficial de Prueba',
            'category' => 'Comunicado',
            'summary' => 'Resumen',
            'content' => 'Contenido extenso de la publicación...',
            'is_visible' => true,
            'author_id' => $this->superAdmin->id,
        ]);

        $act = Activity::create([
            'title' => 'Asamblea Comunitaria de Prueba',
            'category' => 'Asamblea',
            'description' => 'Detalle de la actividad comunal...',
            'location' => 'Caseta Comunal',
            'event_date' => now()->addDays(5),
            'status' => 'scheduled',
            'is_visible' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $doc = Document::create([
            'title' => 'Estatuto de Prueba 2026',
            'category' => 'Estatutos y Acuerdos',
            'file_path' => 'documents/test.pdf',
            'file_name' => 'test.pdf',
            'file_size' => 1048576,
            'mime_type' => 'application/pdf',
            'is_visible' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $msg = ContactMessage::create([
            'name' => 'Comunero Contacto',
            'email' => 'comunero@contacto.com',
            'phone' => '3123456789',
            'territory' => 'El Palmar',
            'message' => 'Mensaje de solicitud',
            'status' => 'pendiente',
        ]);

        $media = MediaItem::create([
            'title' => 'Fotografía Test para CMS',
            'type' => 'image',
            'category' => 'Territorio',
            'file_url' => 'https://ejemplo.com/foto.webp',
            'is_visible' => true,
            'created_by' => $this->superAdmin->id,
        ]);

        $survey = Survey::create([
            'title' => 'Encuesta Territorial 2026',
            'category' => 'Censo Territorial',
            'status' => 'active',
            'is_published' => true,
            'questions' => [
                ['id' => 'q1', 'label' => 'Pregunta 1', 'type' => 'text'],
            ],
            'created_by' => $this->superAdmin->id,
        ]);

        $respondent = User::factory()->create([
            'role' => UserRole::Usuario,
            'name' => 'Comunero Encuestado',
            'email' => 'comunero.encuesta@zanjon.gov.co',
        ]);

        $surveyResponse = SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => $respondent->id,
            'answers' => ['q1' => 'Respuesta de prueba'],
        ]);

        // Rutas que deben responder HTTP 200 sin excepción
        $routes = [
            '/admin',
            '/admin/carousel',
            '/admin/carousel/create',
            "/admin/carousel/{$slide->id}/edit",
            '/admin/board',
            '/admin/board/create',
            "/admin/board/{$board->id}/edit",
            '/admin/territories',
            '/admin/territories/create',
            "/admin/territories/{$territory->id}/edit",
            '/admin/publications',
            '/admin/publications/create',
            "/admin/publications/{$pub->id}/edit",
            '/admin/activities',
            '/admin/activities/create',
            "/admin/activities/{$act->id}/edit",
            '/admin/media',
            '/admin/media/create',
            "/admin/media/{$media->id}/edit",
            '/admin/documents',
            '/admin/documents/create',
            "/admin/documents/{$doc->id}/edit",
            '/admin/messages',
            "/admin/messages/{$msg->id}",
            '/admin/surveys',
            '/admin/surveys-manage/create',
            "/admin/surveys/{$survey->id}",
            "/admin/surveys/{$survey->id}/respond",
            "/admin/surveys/{$survey->id}/responses",
            '/admin/users',
            '/admin/audit',
            '/admin/audit/logins',
        ];

        foreach ($routes as $route) {
            $resp = $this->actingAs($this->superAdmin)->get($route);
            $resp->assertStatus(200, "Falló la ruta admin: {$route}");
        }
    }

    /**
     * Valida que todas las páginas públicas del portal carguen sin error
     */
    public function test_all_public_views_render_successfully(): void
    {
        $publicRoutes = [
            '/',
            '/index2',
            '/nosotros',
            '/territorio',
            '/actividades',
            '/galeria',
            '/actualidad',
            '/videos',
            '/documentos',
            '/contacto',
            '/login',
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200, "Falló la ruta pública: {$route}");
        }
    }
}
