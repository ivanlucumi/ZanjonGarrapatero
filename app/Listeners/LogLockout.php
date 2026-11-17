<?php

namespace App\Listeners;

use App\Services\AuditService;
use Illuminate\Auth\Events\Lockout;

class LogLockout
{
    /**
     * Handle the event.
     */
    public function handle(Lockout $event): void
    {
        $request = $event->request;
        $email = (string) $request->input('email', 'Desconocido');
        $ip = $request->ip() ?? '127.0.0.1';

        // Registrar bloqueo por fuerza bruta en la bitácora de auditoría
        AuditService::log(
            action: 'bloqueo_fuerza_bruta',
            entity: 'Seguridad',
            description: "Bloqueo por exceso de intentos fallidos de inicio de sesión para la cuenta [{$email}] desde la dirección IP {$ip}. Acceso restringido por 1 minuto.",
            properties: [
                'email' => $email,
                'ip' => $ip,
                'duration_minutes' => 1,
            ]
        );
    }
}
