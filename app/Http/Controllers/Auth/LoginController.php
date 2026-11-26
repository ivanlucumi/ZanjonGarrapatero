<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | Maneja la autenticación y control de acceso con límite estricto de intentos,
    | bloqueo temporal de 1 minuto y redirección según rol de usuario.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Número máximo de intentos permitidos antes de activar el bloqueo.
     *
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * Duración en minutos del bloqueo temporal tras exceder los intentos (1 minuto).
     *
     * @var int
     */
    protected $decayMinutes = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Obtener el número máximo de intentos fallidos permitidos.
     */
    public function maxAttempts(): int
    {
        return (int) config('auth.login_max_attempts', $this->maxAttempts);
    }

    /**
     * Obtener la duración en minutos del bloqueo por exceso de intentos (1 minuto).
     */
    public function decayMinutes(): int
    {
        return (int) config('auth.login_decay_minutes', $this->decayMinutes);
    }

    /**
     * Validate the user login request including Google reCAPTCHA ("No soy un robot") when enabled.
     *
     * @throws ValidationException
     */
    protected function validateLogin(Request $request): void
    {
        $rules = [
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        $recaptchaEnabled = (bool) config('services.recaptcha.enabled', false);
        $secretKey = (string) config('services.recaptcha.secret_key', '');

        if ($recaptchaEnabled && $secretKey !== '') {
            $rules['g-recaptcha-response'] = ['required', 'string'];
        }

        $request->validate($rules, [
            'g-recaptcha-response.required' => 'Por favor confirma la verificación "No soy un robot" de Google.',
        ]);

        if ($recaptchaEnabled && $secretKey !== '') {
            $verify = Http::asForm()->timeout(8)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            if (! $verify->successful() || ! ($verify->json('success') === true)) {
                throw ValidationException::withMessages([
                    'g-recaptcha-response' => ['La verificación de Google reCAPTCHA falló o expiró. Inténtalo nuevamente.'],
                ]);
            }
        }
    }

    /**
     * Hook ejecutado inmediatamente después de que el usuario se autentica con éxito.
     *
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Registrar el instante de inicio de sesión para el límite de 1 hora
        $request->session()->put('auth_login_time', now()->timestamp);

        $routeName = $user->role?->dashboardRoute() ?? 'admin.dashboard';

        return redirect()->intended(route($routeName));
    }

    /**
     * Redirige al usuario tras exceder los intentos con respuesta 429 y mensaje descriptivo.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws ValidationException
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $message = "Has superado el límite de {$this->maxAttempts()} intentos fallidos de inicio de sesión. Por motivos de seguridad territorial, tu acceso ha sido bloqueado durante 1 minuto. Intenta de nuevo en {$seconds} segundos.";

        // Flash adicional para que SweetAlert2 lo capture inmediatamente
        session()->flash('error', $message);

        throw ValidationException::withMessages([
            $this->username() => [$message],
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);
    }
}
