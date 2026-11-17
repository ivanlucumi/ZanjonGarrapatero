<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if (empty($roles)) {
            return $next($request);
        }

        if (! $user->hasRole(...$roles)) {
            abort(403, 'Acceso restringido: Su perfil de usuario no cuenta con los permisos necesarios para gestionar este módulo del Consejo Comunitario.');
        }

        return $next($request);
    }
}
