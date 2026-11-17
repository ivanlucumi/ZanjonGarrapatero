<?php

namespace App\Listeners;

use App\Models\LoginLog;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Request;

class LogSuccessfulLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;
        $ip = Request::ip() ?? '127.0.0.1';
        $userAgent = Request::userAgent() ?? 'Navegador Web';

        // 1. Registro dedicado de accesos / inicios de sesión
        LoginLog::query()->create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'role' => $user->role?->label() ?? 'Usuario',
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'login_at' => now(),
        ]);

        // 2. Registro en auditoría general de plataforma
        AuditService::log(
            action: 'inicio_sesion',
            entity: 'Autenticación',
            description: "Inicio de sesión exitoso de {$user->name} ({$user->email}) desde la dirección IP {$ip}.",
            properties: [
                'email' => $user->email,
                'role' => $user->role?->value,
                'ip' => $ip,
            ],
            user: $user
        );
    }
}
