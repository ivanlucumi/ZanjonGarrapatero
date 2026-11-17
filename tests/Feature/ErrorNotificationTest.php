<?php

namespace Tests\Feature;

use App\Mail\SystemErrorOccurredMail;
use App\Services\ErrorNotificationService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class ErrorNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('logging.error_notification.email', 'admin@zanjon.gov.co');
        Config::set('logging.error_notification.enabled', true);
    }

    /**
     * Valida que al ocurrir una excepción del sistema, se registre en el Log con su código y contexto.
     */
    public function test_exception_is_logged_with_error_code_and_structured_context(): void
    {
        Log::spy();

        $exception = new RuntimeException('Error de conexión con la pasarela comunitaria', 503);

        app(ErrorNotificationService::class)->handle($exception);

        Log::shouldHaveReceived('error')
            ->once()
            ->withArgs(function (string $message, array $context): bool {
                return str_contains($message, '[Código: 503]')
                    && $context['error_code'] === 503
                    && $context['exception_class'] === RuntimeException::class
                    && str_contains($context['message'], 'Error de conexión con la pasarela comunitaria')
                    && isset($context['file'], $context['line'], $context['url'], $context['ip'], $context['timestamp']);
            });
    }

    /**
     * Valida que al reportarse una excepción, se despache un correo al email configurado en el .env con el código de error.
     */
    public function test_exception_dispatches_email_notification_with_error_code(): void
    {
        Mail::fake();

        $customCodeException = new Exception('Falla crítica en servicio de archivos', 500);

        report($customCodeException);

        Mail::assertSent(SystemErrorOccurredMail::class, function (SystemErrorOccurredMail $mail): bool {
            $envelope = $mail->envelope();

            return $mail->hasTo('admin@zanjon.gov.co')
                && str_contains($envelope->subject, '[ALERTA ERROR 500]')
                && $mail->errorData['errorCode'] === 500
                && str_contains($mail->errorData['errorMessage'], 'Falla crítica en servicio de archivos')
                && $mail->errorData['exceptionClass'] === Exception::class;
        });
    }

    /**
     * Valida la resolución de códigos para excepciones HTTP específicas (ej. HttpException 502 / 500).
     */
    public function test_http_exception_resolves_status_code_in_notification(): void
    {
        Mail::fake();

        $httpException = new HttpException(502, 'Bad Gateway en servicio territorial externo');

        report($httpException);

        Mail::assertSent(SystemErrorOccurredMail::class, function (SystemErrorOccurredMail $mail): bool {
            return $mail->errorData['errorCode'] === 502
                && str_contains($mail->envelope()->subject, '[ALERTA ERROR 502]');
        });
    }

    /**
     * Valida la renderización del contenido HTML y Texto plano del Mailable.
     */
    public function test_system_error_mailable_renders_both_html_and_plain_text_with_details(): void
    {
        $payload = [
            'errorCode' => 500,
            'errorMessage' => 'Error de prueba para renderizado de correo',
            'errorMessageSummary' => 'Error de prueba',
            'exceptionClass' => RuntimeException::class,
            'file' => 'C:\\laragon\\www\\ZanjonGarrapatero\\app\\Test.php',
            'line' => 42,
            'url' => 'http://localhost/admin/test-error',
            'method' => 'POST',
            'ip' => '192.168.1.50',
            'user' => 'ID #1 - Gobernador Mayor (admin@zanjon.gov.co) [Rol: super_admin]',
            'user_agent' => 'Mozilla/5.0 Test Browser',
            'environment' => 'production',
            'timestamp' => '2026-11-16 20:45:00 UTC',
            'trace' => "#0 /app/Test.php(42): doSomething()\n#1 /app/Kernel.php(10): run()",
        ];

        $mailable = new SystemErrorOccurredMail($payload);

        // Verificación del asunto con código de error
        $this->assertStringContainsString('[ALERTA ERROR 500]', $mailable->envelope()->subject);

        // Verificación en HTML
        $mailable->assertSeeInHtml('CÓDIGO: 500');
        $mailable->assertSeeInHtml('Error de prueba para renderizado de correo');
        $mailable->assertSeeInHtml('http://localhost/admin/test-error');
        $mailable->assertSeeInHtml('192.168.1.50');
        $mailable->assertSeeInHtml('Gobernador Mayor');
        $mailable->assertSeeInHtml('C:\\laragon\\www\\ZanjonGarrapatero\\app\\Test.php');

        // Verificación en Texto Plano
        $mailable->assertSeeInText('CÓDIGO DE ERROR: [500]');
        $mailable->assertSeeInText('Error de prueba para renderizado de correo');
        $mailable->assertSeeInText('http://localhost/admin/test-error');
        $mailable->assertSeeInText('192.168.1.50');
    }

    /**
     * Valida que cuando la notificación está deshabilitada en la configuración, no se despache correo.
     */
    public function test_email_is_not_sent_when_notification_is_disabled(): void
    {
        Mail::fake();

        Config::set('logging.error_notification.enabled', false);

        report(new RuntimeException('Error cuando está desactivado el envío'));

        Mail::assertNothingSent();
    }

    /**
     * Valida que cuando no hay correo destino configurado, se omite el envío sin generar excepciones secundarias.
     */
    public function test_email_is_not_sent_when_recipient_email_is_empty(): void
    {
        Mail::fake();

        Config::set('logging.error_notification.email', null);
        Config::set('mail.error_notification_email', null);

        report(new RuntimeException('Error sin correo destinatario'));

        Mail::assertNothingSent();
    }

    /**
     * Valida que las excepciones de validación de formulario comunes no disparen falsas alarmas de fallo de sistema.
     */
    public function test_validation_exceptions_do_not_dispatch_system_error_notifications(): void
    {
        Mail::fake();

        $validator = validator([], ['email' => 'required']);
        $validationException = new ValidationException($validator);

        report($validationException);

        Mail::assertNothingSent();
    }

    /**
     * Valida que las excepciones de cliente como 404 Not Found o 403 Forbidden no disparen correos de fallo crítico.
     */
    public function test_client_http_exceptions_like_404_are_not_emailed(): void
    {
        Mail::fake();

        $notFound = new NotFoundHttpException('Ruta no encontrada');

        report($notFound);

        Mail::assertNothingSent();
    }
}
