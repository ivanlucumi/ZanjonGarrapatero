@extends('layouts.app')

@section('content')
<div class="wrap">
    <div class="login-split">
        {{-- Tarjeta Institucional de Presentación y Selección de Proyecto --}}
        <div class="login-hero-card">
            <div class="d-flex align-items-center gap-3 mb-4">
                <img src="{{ asset('logo/logo.webp') }}" alt="Logo Consejo Comunitario Zanjón de Garrapatero" width="56" height="56" class="brand-mark">
                <div>
                    <h2 class="serif h4 mb-0 text-white">Zanjón de Garrapatero</h2>
                    <small style="color: var(--brand-accent); letter-spacing: 0.12em;" class="text-uppercase fw-bold">Consejo Comunitario Afro</small>
                </div>
            </div>

            <h1 class="serif display-6 mb-3 text-white">Plataforma digital territorial</h1>
            <p class="mb-4" style="color: rgba(244, 241, 234, 0.88); line-height: 1.7;">
                Sistema de información, censo y memoria colectiva para la protección de los derechos étnicos y la autonomía de nuestras 8 veredas en el departamento del Cauca.
            </p>

            <div class="login-feature-item">
                <i class="bi bi-shield-shaded"></i>
                <div>
                    <strong class="d-block text-white">Ley 70 de 1993</strong>
                    <small style="color: rgba(244, 241, 234, 0.75);">Garantía de derechos colectivos y titulación ancestral.</small>
                </div>
            </div>

            <div class="login-feature-item">
                <i class="bi bi-people"></i>
                <div>
                    <strong class="d-block text-white">Censo y avales comunitarios</strong>
                    <small style="color: rgba(244, 241, 234, 0.75);">Atención transparente y sin intermediarios para las familias.</small>
                </div>
            </div>
        </div>

        {{-- Formulario Glassmorphism de Inicio de Sesión --}}
        <div>
            <div class="glass-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="glass-pill glass-pill-gold mb-2">Acceso seguro</span>
                    <h2 class="serif h3 mb-1" style="color: var(--brand-primary);">Ingreso al Sistema</h2>
                    <p class="small" style="color: var(--text-muted);">
                        Ingresa con tus credenciales institucionales o de afiliado
                    </p>
                </div>

                <form method="POST" action="{{ route('login') }}" novalidate>
                    @csrf

                    <div class="form-glass-group mb-3">
                        <label for="email" class="form-glass-label">{{ __('Correo Electrónico') }} <span class="text-danger">*</span></label>
                        <input id="email" type="email" class="form-glass-input @error('email') border-danger @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tu.correo@ejemplo.com">

                        @error('email')
                            <div class="form-glass-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="form-glass-group mb-3">
                        <label for="password" class="form-glass-label">{{ __('Contraseña') }} <span class="text-danger">*</span></label>
                        <input id="password" type="password" class="form-glass-input @error('password') border-danger @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">

                        @error('password')
                            <div class="form-glass-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="remember" style="color: var(--text-muted);">
                                {{ __('Recordarme en este equipo') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="btn-link small" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </div>

                    {{-- Google reCAPTCHA v2 ("No soy un robot") --}}
                    <div class="mb-4 d-flex flex-column align-items-center">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        @error('g-recaptcha-response')
                            <div class="form-glass-feedback mt-2 text-center">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-main w-100 justify-content-center py-3 fs-6">
                        <i class="bi bi-box-arrow-in-right me-2"></i> {{ __('Iniciar Sesión') }}
                    </button>
                </form>

                @if (Route::has('register'))
                    <div class="text-center mt-3 small" style="color: var(--text-muted);">
                        ¿Aún no tienes registro?
                        <a href="{{ route('register') }}" class="btn-link ms-1 fw-bold">Solicitar afiliación comunitaria</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?hl=es" async defer></script>
@endpush
@endsection
