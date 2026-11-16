@extends('layouts.app')

@section('content')
<div class="wrap">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="glass-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('logo/logo.webp') }}" alt="Logo Consejo Comunitario Zanjón de Garrapatero" width="56" height="56" class="brand-mark mb-2">
                    <h1 class="serif h3 mb-1" style="color: var(--brand-primary);">Registro Institucional</h1>
                    <p class="small" style="color: var(--text-muted);">
                        Crea una cuenta para vincularte a los procesos y consultas comunitarias
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" novalidate>
                    @csrf

                    <div class="form-glass-group mb-3">
                        <label for="name" class="form-glass-label">{{ __('Nombre Completo') }} <span class="text-danger">*</span></label>
                        <input id="name" type="text" class="form-glass-input @error('name') border-danger @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nombres y apellidos">

                        @error('name')
                            <div class="form-glass-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="form-glass-group mb-3">
                        <label for="email" class="form-glass-label">{{ __('Correo Electrónico') }} <span class="text-danger">*</span></label>
                        <input id="email" type="email" class="form-glass-input @error('email') border-danger @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="ejemplo@correo.com">

                        @error('email')
                            <div class="form-glass-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="form-glass-group mb-3">
                        <label for="password" class="form-glass-label">{{ __('Contraseña') }} <span class="text-danger">*</span></label>
                        <input id="password" type="password" class="form-glass-input @error('password') border-danger @enderror" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres">

                        @error('password')
                            <div class="form-glass-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="form-glass-group mb-4">
                        <label for="password-confirm" class="form-glass-label">{{ __('Confirmar Contraseña') }} <span class="text-danger">*</span></label>
                        <input id="password-confirm" type="password" class="form-glass-input" name="password_confirmation" required autocomplete="new-password" placeholder="Repite la contraseña">
                    </div>

                    <button type="submit" class="btn btn-main w-100 justify-content-center py-3 fs-6">
                        <i class="bi bi-person-check me-2"></i> {{ __('Completar Registro') }}
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top small" style="border-color: var(--line) !important; color: var(--text-muted);">
                    ¿Ya tienes una cuenta registrada?
                    <a href="{{ route('login') }}" class="btn-link ms-1 fw-bold">Iniciar sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
