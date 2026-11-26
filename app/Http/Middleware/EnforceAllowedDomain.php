<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceAllowedDomain
{
    /**
     * Handle an incoming request and block execution on unauthorized domains or subdomains.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('allowed_domain.enabled', true)) {
            return $next($request);
        }

        $allowedDomains = config('allowed_domain.domains', []);
        $currentHost = strtolower($request->getHost());

        if (! $this->isHostAllowed($currentHost, $allowedDomains)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Dominio no autorizado para ejecutar acciones en esta plataforma.',
                ], Response::HTTP_FORBIDDEN);
            }

            return response()->view('errors.domain-blocked', [], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }

    /**
     * Determine whether the current host matches an allowed domain or any of its subdomains.
     *
     * @param  array<int, string>  $allowedDomains
     */
    protected function isHostAllowed(string $host, array $allowedDomains): bool
    {
        foreach ($allowedDomains as $domain) {
            $domain = ltrim(strtolower(trim($domain)), '*.');
            if ($domain === '') {
                continue;
            }

            if ($host === $domain || str_ends_with($host, '.'.$domain)) {
                return true;
            }
        }

        return false;
    }
}
