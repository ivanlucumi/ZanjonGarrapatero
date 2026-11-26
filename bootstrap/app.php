<?php

use App\Http\Middleware\EnforceAllowedDomain;
use App\Http\Middleware\EnforceMaxSessionLifetime;
use App\Http\Middleware\EnsureUserHasRole;
use App\Services\ErrorNotificationService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
            'session.lifetime' => EnforceMaxSessionLifetime::class,
        ]);

        $middleware->prependToGroup('web', EnforceAllowedDomain::class);
        $middleware->appendToGroup('web', EnforceMaxSessionLifetime::class);

        $middleware->redirectUsersTo(
            fn (Request $request): string => route($request->user()?->role?->dashboardRoute() ?? 'admin.dashboard'),
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->stopIgnoring(HttpException::class);

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->report(function (Throwable $e): void {
            app(ErrorNotificationService::class)->handle($e);
        });
    })->create();
