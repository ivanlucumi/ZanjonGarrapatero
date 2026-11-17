<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_session_lifetime_is_configured_to_one_hour(): void
    {
        $this->assertSame(60, (int) config('session.lifetime'), 'El tiempo de vida de la sesión debe ser de 60 minutos (1 hora).');
    }

    public function test_login_attempts_are_limited_and_locked_out_after_5_failures(): void
    {
        $user = User::factory()->create([
            'email' => 'seguridad@zanjon.gov.co',
            'password' => bcrypt('claveSegura123'),
        ]);

        // Realizar 5 intentos fallidos consecutivos
        for ($i = 1; $i <= 5; $i++) {
            $response = $this->from('/login')->post('/login', [
                'email' => $user->email,
                'password' => 'claveErronea',
            ]);

            $response->assertRedirect('/login');
            $response->assertSessionHasErrors('email');
            $this->assertGuest();
        }

        // El sexto intento debe ser bloqueado y redirigir con mensaje de penalización
        $lockoutResponse = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'claveErronea',
        ]);

        $lockoutResponse->assertRedirect('/login');
        $lockoutResponse->assertSessionHasErrors('email');
        $lockoutResponse->assertSessionHas('error');

        // Para peticiones API / JSON debe responder con HTTP 429 Too Many Requests
        $jsonLockoutResponse = $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'claveErronea',
        ]);
        $jsonLockoutResponse->assertStatus(429);

        // Se debió registrar el evento de bloqueo en la auditoría general de seguridad
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'bloqueo_fuerza_bruta',
            'entity_type' => 'Seguridad',
        ]);

        $auditLog = AuditLog::query()
            ->where('action', 'bloqueo_fuerza_bruta')
            ->latest('id')
            ->first();

        $this->assertNotNull($auditLog);
        $this->assertStringContainsString('seguridad@zanjon.gov.co', $auditLog->description);
        $this->assertStringContainsString('1 minuto', $auditLog->description);
    }

    public function test_successful_login_initializes_session_lifetime_timer(): void
    {
        $user = User::factory()->create([
            'email' => 'comunero@zanjon.gov.co',
            'password' => bcrypt('claveValida123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'claveValida123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
        $this->assertNotEmpty(session('auth_login_time'));
    }

    public function test_session_is_terminated_after_one_hour_of_activity(): void
    {
        $user = User::factory()->create();

        // Simular que el usuario inició sesión hace 61 minutos (superando 1 hora)
        $this->actingAs($user);
        session(['auth_login_time' => now()->subMinutes(61)->timestamp]);

        $response = $this->get('/admin');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_session_within_one_hour_remains_active(): void
    {
        $user = User::factory()->create();

        // Simular que el usuario inició sesión hace 20 minutos (dentro de la hora)
        $this->actingAs($user);
        session(['auth_login_time' => now()->subMinutes(20)->timestamp]);

        $response = $this->get('/admin');

        $response->assertStatus(200);
        $this->assertAuthenticatedAs($user);
    }
}
