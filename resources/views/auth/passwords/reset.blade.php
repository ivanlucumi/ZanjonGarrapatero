@extends('layouts.app')

@section('content')
<div class="wrap">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="glass-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('logo/logo.webp') }}" alt="Logo Consejo Comunitario Zanjón de Garrapatero" width="56" height="56" class="brand-mark mb-2">
                    <h1 class="serif h3 mb-1" style="color: var(--brand-primary);">Establecer Nueva Contraseña</h1>
                    <p class="small" style="color: var(--text-muted);">
                        Ingresa y confirma tu nueva clave de acceso
                    </p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" novalidate>
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-glass-group mb-3">
                        <label for="email" class="form-glass-label">{{ __('Correo Electrónico') }} <span class="text-danger">*</span></label>
                        <input id="email" type="email" class="form-glass-input @error('email') border-danger @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <div class="form-glass-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="form-glass-group mb-3">
                        <label for="password" class="form-glass-label">{{ __('Nueva Contraseña') }} <span class="text-danger">*</span></label>
                        <input id="password" type="password" class="form-glass-input @error('password') border-danger @enderror" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres">

                        @error('password')
                            <div class="form-glass-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="form-glass-group mb-4">
                        <label for="password-confirm" class="form-glass-label">{{ __('Confirmar Nueva Contraseña') }} <span class="text-danger">*</span></label>
                        <input id="password-confirm" type="password" class="form-glass-input" name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn btn-main w-100 justify-content-center py-3 fs-6">
                        <i class="bi bi-shield-check me-2"></i> {{ __('Actualizar Contraseña') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
