<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnforceMaxSessionLifetime
{
    /**
     * Maneja el control de expiración estricta de sesión tras 1 hora (60 minutos).
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            $loginTime = $request->session()->get('auth_login_time');
            $maxLifetimeSeconds = (int) config('session.lifetime', 60) * 60;

            if ($loginTime && (now()->timestamp - $loginTime > $maxLifetimeSeconds)) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Tu sesión ha expirado tras 1 hora de actividad.',
                    ], Response::HTTP_UNAUTHORIZED);
                }

                return redirect()->route('login')->with(
                    'error',
                    'Tu sesión ha expirado tras 1 hora de actividad por políticas de seguridad territorial. Por favor, ingresa de nuevo.'
                );
            }

            if (! $loginTime) {
                $request->session()->put('auth_login_time', now()->timestamp);
            }
        }

        return $next($request);
    }
}
