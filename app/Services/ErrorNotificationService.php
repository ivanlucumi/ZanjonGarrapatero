<?php

namespace App\Services;

use App\Mail\SystemErrorOccurredMail;
use App\Models\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ErrorNotificationService
{
    /**
     * Procesar, registrar en log y notificar por correo electrónico cualquier
     * excepción no controlada o fallo del sistema.
     */
    public function handle(Throwable $throwable): void
    {
        // Si el manejador interno de Laravel determina que no debe reportarse (ej. 404 común o validación), se descarta.
        if (! $this->shouldReportException($throwable)) {
            return;
        }

        $errorData = $this->buildErrorData($throwable);

        // 1. Validar y registrar en los logs del sistema con toda la estructura
        $this->logError($errorData, $throwable);

        // 2. Notificar por correo electrónico al destinatario configurado en .env
        $this->notifyByEmail($errorData, $throwable);
    }

    /**
     * Determina si la excepción debe ser reportada.
     */
    protected function shouldReportException(Throwable $throwable): bool
    {
        // En excepciones HTTP, únicamente reportar fallos del servidor (5xx: 500, 502, 503, etc.)
        if ($throwable instanceof HttpExceptionInterface) {
            return $throwable->getStatusCode() >= 500;
        }

        /** @var ExceptionHandler $handler */
        $handler = app(ExceptionHandler::class);

        return $handler->shouldReport($throwable);
    }

    /**
     * Construir la estructura completa de información y contexto del error.
     *
     * @return array{
     *     errorCode: string|int,
     *     errorMessage: string,
     *     errorMessageSummary: string,
     *     exceptionClass: string,
     *     file: string,
     *     line: int,
     *     url: string,
     *     method: string,
     *     ip: string,
     *     user: ?string,
     *     user_agent: string,
     *     environment: string,
     *     timestamp: string,
     *     trace: string
     * }
     */
    public function buildErrorData(Throwable $throwable): array
    {
        $errorCode = $this->resolveErrorCode($throwable);
        $isConsole = app()->runningInConsole();

        $url = $isConsole ? 'CLI / Consola Artisan' : (request()?->fullUrl() ?? 'N/A');
        $method = $isConsole ? 'CLI' : (request()?->method() ?? 'GET');
        $ip = $isConsole ? '127.0.0.1' : (request()?->ip() ?? 'N/A');
        $userAgent = $isConsole ? 'PHP CLI Console' : (request()?->userAgent() ?? 'N/A');

        $userDescription = 'No autenticado / Invitado';
        if (! $isConsole && Auth::check()) {
            /** @var User $authUser */
            $authUser = Auth::user();
            $roleValue = $authUser->role?->value ?? 'sin-rol';
            $userDescription = "ID #{$authUser->id} - {$authUser->name} ({$authUser->email}) [Rol: {$roleValue}]";
        }

        $rawMessage = $throwable->getMessage();
        $messageSummary = Str::limit($rawMessage, 60, '...') ?: 'Excepción del sistema';

        // Formatear las primeras 20 líneas de la traza para legibilidad
        $traceExcerpt = $this->formatTraceExcerpt($throwable);

        return [
            'errorCode' => $errorCode,
            'errorMessage' => $rawMessage,
            'errorMessageSummary' => $messageSummary,
            'exceptionClass' => get_class($throwable),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'url' => $url,
            'method' => $method,
            'ip' => $ip,
            'user' => $userDescription,
            'user_agent' => $userAgent,
            'environment' => app()->environment(),
            'timestamp' => now()->format('Y-m-d H:i:s T'),
            'trace' => $traceExcerpt,
        ];
    }

    /**
     * Resolver el código numérico o alfanumérico más representativo del error.
     */
    protected function resolveErrorCode(Throwable $throwable): string|int
    {
        $code = $throwable->getCode();

        if (! empty($code)) {
            return $code;
        }

        if ($throwable instanceof HttpExceptionInterface) {
            return $throwable->getStatusCode();
        }

        return 500;
    }

    /**
     * Registrar el evento en el log de Laravel con toda la metadata requerida.
     *
     * @param  array<string, mixed>  $errorData
     */
    protected function logError(array $errorData, Throwable $throwable): void
    {
        Log::error("Fallo del Sistema [Código: {$errorData['errorCode']}]: {$errorData['errorMessage']}", [
            'error_code' => $errorData['errorCode'],
            'exception_class' => $errorData['exceptionClass'],
            'message' => $errorData['errorMessage'],
            'file' => $errorData['file'],
            'line' => $errorData['line'],
            'url' => $errorData['url'],
            'method' => $errorData['method'],
            'ip' => $errorData['ip'],
            'user' => $errorData['user'],
            'environment' => $errorData['environment'],
            'timestamp' => $errorData['timestamp'],
            'stack_trace' => $throwable->getTraceAsString(),
        ]);
    }

    /**
     * Notificar por correo electrónico a la dirección registrada en el .env.
     *
     * @param  array<string, mixed>  $errorData
     */
    protected function notifyByEmail(array $errorData, Throwable $throwable): void
    {
        $recipientEmail = config('logging.error_notification.email')
            ?: config('mail.error_notification_email');

        $isEnabled = (bool) config('logging.error_notification.enabled', true);

        if (empty($recipientEmail) || ! $isEnabled) {
            return;
        }

        // En entornos de producción y local, aplicar limitación contra tormentas de correos idénticos
        if (! app()->environment('testing')) {
            $errorSignature = md5($errorData['exceptionClass'].$errorData['file'].$errorData['line'].$errorData['errorCode']);
            $cacheKey = "system_error_mail_throttle:{$errorSignature}";

            if (Cache::has($cacheKey)) {
                Log::info("Notificación por correo omitida temporalmente (ya enviada en los últimos 5 minutos para este mismo error): [{$errorData['errorCode']}] {$errorData['exceptionClass']}");

                return;
            }

            // Bloquear duplicados del mismo error durante 5 minutos
            Cache::put($cacheKey, true, now()->addMinutes(5));
        }

        try {
            Mail::to($recipientEmail)->send(new SystemErrorOccurredMail($errorData));
        } catch (Throwable $mailException) {
            Log::warning('No fue posible despachar la notificación por correo electrónico del error del sistema: '.$mailException->getMessage(), [
                'mail_error' => $mailException->getMessage(),
                'original_error' => $errorData['errorCode'],
            ]);
        }
    }

    /**
     * Extrae un fragmento conciso y legible del stack trace.
     */
    protected function formatTraceExcerpt(Throwable $throwable): string
    {
        $traceLines = explode("\n", $throwable->getTraceAsString());
        $sliced = array_slice($traceLines, 0, 20);

        return implode("\n", $sliced);
    }
}
