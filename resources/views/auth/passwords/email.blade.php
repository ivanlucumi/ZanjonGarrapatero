@extends('layouts.app')

@section('content')
<div class="wrap">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="glass-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('logo/logo.webp') }}" alt="Logo Consejo Comunitario Zanjón de Garrapatero" width="56" height="56" class="brand-mark mb-2">
                    <h1 class="serif h3 mb-1" style="color: var(--brand-primary);">Recuperar Contraseña</h1>
                    <p class="small" style="color: var(--text-muted);">
                        Ingresa tu correo para recibir las instrucciones de restablecimiento
                    </p>
                </div>

                @if (session('status'))
                    <div class="alert alert-glass-success mb-4 d-flex align-items-center gap-2" role="alert">
                        <i class="bi bi-check-circle-fill" style="color: var(--brand-accent);"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf

                    <div class="form-glass-group mb-4">
                        <label for="email" class="form-glass-label">{{ __('Correo Electrónico') }} <span class="text-danger">*</span></label>
                        <input id="email" type="email" class="form-glass-input @error('email') border-danger @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="ejemplo@correo.com">

                        @error('email')
                            <div class="form-glass-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-main w-100 justify-content-center py-3 fs-6">
                        <i class="bi bi-send-check me-2"></i> {{ __('Enviar Enlace de Recuperación') }}
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top small" style="border-color: var(--line) !important; color: var(--text-muted);">
                    <a href="{{ route('login') }}" class="btn-link fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
