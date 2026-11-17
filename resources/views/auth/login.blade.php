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

            {{-- Separador con Efecto Relieve y Dota --}}
            <div class="relief-divider relief-compact" aria-hidden="true"></div>

            {{-- Selector de Proyectos para Presentación --}}
            <div class="pt-2">
                <span class="small d-block mb-2 text-uppercase fw-bold" style="color: var(--brand-accent); font-size: 0.72rem; letter-spacing: 0.1em;">
                    <i class="bi bi-layers-half me-1"></i> Explorar diseños del proyecto:
                </span>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('index') }}" class="btn btn-ghost py-2 px-3 small">
                        <i class="bi bi-layout-text-window-reverse me-1"></i> Diseño 1. Barra superior
                    </a>
                    <a href="{{ route('index2') }}" class="btn btn-main py-2 px-3 small">
                        <i class="bi bi-layout-sidebar-reverse me-1"></i> Diseño 2. Menú lateral
                    </a>
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

                    <button type="submit" class="btn btn-main w-100 justify-content-center py-3 fs-6">
                        <i class="bi bi-box-arrow-in-right me-2"></i> {{ __('Iniciar Sesión') }}
                    </button>
                </form>

                {{-- Acceso Rápido Demo para los 3 Roles --}}
                <div class="mt-4 pt-3 border-top" style="border-color: var(--line) !important;">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="small fw-bold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.08em; color: var(--brand-accent);">
                            <i class="bi bi-key-fill me-1"></i> Cuentas Demo por Rol:
                        </span>
                        <span class="small text-muted" style="font-size: 0.7rem;">(Clic para autocompletar)</span>
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm text-start py-2 px-3 rounded-3 demo-fill-btn" data-email="superadmin@zanjon.gov.co" data-password="password123" style="font-size: 0.78rem;">
                            <strong class="d-block text-dark"><i class="bi bi-shield-shaded text-warning me-1"></i> Super Admin ("Ve Todo"):</strong>
                            <span class="text-muted">superadmin@zanjon.gov.co &bull; password123</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm text-start py-2 px-3 rounded-3 demo-fill-btn" data-email="admin@zanjon.gov.co" data-password="password123" style="font-size: 0.78rem;">
                            <strong class="d-block text-dark"><i class="bi bi-clipboard2-data text-primary me-1"></i> Admin Encuestas ("Encuesta"):</strong>
                            <span class="text-muted">admin@zanjon.gov.co &bull; password123</span>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm text-start py-2 px-3 rounded-3 demo-fill-btn" data-email="usuario@zanjon.gov.co" data-password="password123" style="font-size: 0.78rem;">
                            <strong class="d-block text-dark"><i class="bi bi-person-check text-success me-1"></i> Comunero ("Usuario"):</strong>
                            <span class="text-muted">usuario@zanjon.gov.co &bull; password123</span>
                        </button>
                    </div>
                </div>

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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.demo-fill-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var emailInput = document.getElementById('email');
                var passInput = document.getElementById('password');
                if (emailInput && passInput) {
                    emailInput.value = this.dataset.email;
                    passInput.value = this.dataset.password;
                    emailInput.focus();
                }
            });
        });
    });
</script>
@endsection
