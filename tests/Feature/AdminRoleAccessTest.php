<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard_and_is_redirected_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_super_admin_can_access_dashboard_and_views_everything(): void
    {
        $superAdmin = User::factory()->create([
            'role' => UserRole::SuperAdmin,
            'vereda' => 'Zanjón de Garrapatero',
        ]);

        $response = $this->actingAs($superAdmin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Super Administrador');
        $response->assertSee('Comuneros Registrados');
        $response->assertSee('Total de Encuestas');
        $response->assertSee('Gestionar Usuarios');
    }

    public function test_super_admin_can_access_users_management_and_update_roles(): void
    {
        $superAdmin = User::factory()->create([
            'role' => UserRole::SuperAdmin,
        ]);

        $targetUser = User::factory()->create([
            'role' => UserRole::Usuario,
            'vereda' => 'San Antonio',
        ]);

        // Access users index
        $indexResponse = $this->actingAs($superAdmin)->get('/admin/users');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee($targetUser->name);

        // Update target user role to Admin
        $patchResponse = $this->actingAs($superAdmin)->patch("/admin/users/{$targetUser->id}/role", [
            'role' => UserRole::Admin->value,
            'vereda' => 'Dominguillo',
            'phone' => '3123456789',
            'document_number' => '1029384756',
        ]);

        $patchResponse->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'role' => UserRole::Admin->value,
            'vereda' => 'Dominguillo',
        ]);
    }

    public function test_admin_can_access_dashboard_and_survey_management(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Administrador de Encuestas');
        $response->assertSee('Encuestas Activas');

        // Can access survey creation
        $createResponse = $this->actingAs($admin)->get('/admin/surveys-manage/create');
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Diseño de Encuesta Comunitario');
    }

    public function test_admin_is_forbidden_from_users_management_with_403(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_usuario_can_access_dashboard_and_view_pending_surveys(): void
    {
        $usuario = User::factory()->create([
            'role' => UserRole::Usuario,
            'vereda' => 'Zanjón de Garrapatero',
        ]);

        $survey = Survey::create([
            'title' => 'Encuesta de Prueba Territorial',
            'slug' => 'encuesta-prueba-territorial',
            'category' => 'Censo Territorial',
            'target_vereda' => null,
            'status' => 'active',
            'is_published' => true,
            'questions' => [
                ['id' => 'q_test', 'label' => '¿Tiene predio propio?', 'type' => 'text'],
            ],
        ]);

        $response = $this->actingAs($usuario)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Comunero / Miembro del Territorio');
        $response->assertSee('Encuesta de Prueba Territorial');
        $response->assertSee('Diligenciar Ahora');
    }

    public function test_usuario_is_forbidden_from_survey_creation_with_403(): void
    {
        $usuario = User::factory()->create([
            'role' => UserRole::Usuario,
        ]);

        $response = $this->actingAs($usuario)->get('/admin/surveys-manage/create');

        $response->assertStatus(403);
    }

    public function test_usuario_is_forbidden_from_users_management_with_403(): void
    {
        $usuario = User::factory()->create([
            'role' => UserRole::Usuario,
        ]);

        $response = $this->actingAs($usuario)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_usuario_can_submit_survey_response(): void
    {
        $usuario = User::factory()->create([
            'role' => UserRole::Usuario,
            'vereda' => 'Zanjón de Garrapatero',
        ]);

        $survey = Survey::create([
            'title' => 'Censo de Acceso a Fuentes de Agua',
            'slug' => 'censo-agua-2026',
            'category' => 'Medio Ambiente y Agua',
            'status' => 'active',
            'is_published' => true,
            'questions' => [
                ['id' => 'acceso_agua', 'label' => '¿Tiene acueducto?', 'type' => 'text'],
            ],
        ]);

        // Get answering form
        $formResponse = $this->actingAs($usuario)->get("/admin/surveys/{$survey->id}/respond");
        $formResponse->assertStatus(200);
        $formResponse->assertSee('Censo de Acceso a Fuentes de Agua');

        // Submit response
        $postResponse = $this->actingAs($usuario)->post("/admin/surveys/{$survey->id}/respond", [
            'survey_id' => $survey->id,
            'answers' => [
                'acceso_agua' => 'Sí, acueducto veredal comunitario',
            ],
            'notes' => 'Llega agua tres días por semana.',
        ]);

        $postResponse->assertRedirect('/admin');
        $postResponse->assertSessionHas('success');

        $this->assertDatabaseHas('survey_responses', [
            'survey_id' => $survey->id,
            'user_id' => $usuario->id,
            'notes' => 'Llega agua tres días por semana.',
        ]);
    }

    public function test_authenticated_user_can_use_demo_role_switcher(): void
    {
        $usuario = User::factory()->create([
            'role' => UserRole::Usuario,
        ]);

        $response = $this->actingAs($usuario)->post('/admin/demo-switch-role', [
            'role' => UserRole::SuperAdmin->value,
        ]);

        $response->assertRedirect('/admin');
        $response->assertSessionHas('success');

        $this->assertEquals(UserRole::SuperAdmin, $usuario->fresh()->role);
    }

    public function test_home_redirects_to_admin_dashboard(): void
    {
        $usuario = User::factory()->create([
            'role' => UserRole::Usuario,
        ]);

        $response = $this->actingAs($usuario)->get('/home');

        $response->assertRedirect(route('admin.surveys.index'));
    }

    public function test_admin_layout_renders_with_left_sidebar_navigation(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::SuperAdmin,
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('admin-sidebar');
        $response->assertSee('admin-sidebar-brand');
        $response->assertSee('admin-sidebar-nav');
        $response->assertSee('admin-main');
        $response->assertDontSee('admin-role-switcher-grid');
        $response->assertDontSee('Simular Rol (Demo)');
        $response->assertSee('admin-mobile-topbar');
    }

    public function test_standard_user_is_forbidden_from_all_cms_and_document_routes(): void
    {
        $usuario = User::factory()->create([
            'role' => UserRole::Usuario,
        ]);

        $protectedRoutes = [
            '/admin/carousel/create',
            '/admin/documents/create',
            '/admin/board/create',
            '/admin/territories/create',
            '/admin/media/create',
            '/admin/publications/create',
            '/admin/audit',
        ];

        foreach ($protectedRoutes as $route) {
            $response = $this->actingAs($usuario)->get($route);
            $response->assertStatus(403);
        }
    }

    public function test_admin_and_super_admin_can_access_cms_creation_forms(): void
    {
        $admin = User::factory()->create([
            'role' => UserRole::Admin,
        ]);

        $creationRoutes = [
            '/admin/carousel/create',
            '/admin/documents/create',
            '/admin/board/create',
            '/admin/territories/create',
            '/admin/media/create',
            '/admin/publications/create',
        ];

        foreach ($creationRoutes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }
    }
}
