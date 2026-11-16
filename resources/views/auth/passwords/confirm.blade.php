@extends('layouts.app')

@section('content')
<div class="wrap">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="glass-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('logo/logo.webp') }}" alt="Logo Consejo Comunitario Zanjón de Garrapatero" width="56" height="56" class="brand-mark mb-2">
                    <h1 class="serif h3 mb-1" style="color: var(--brand-primary);">Confirmar Contraseña</h1>
                    <p class="small" style="color: var(--text-muted);">
                        Por favor confirma tu contraseña antes de continuar
                    </p>
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" novalidate>
                    @csrf

                    <div class="form-glass-group mb-4">
                        <label for="password" class="form-glass-label">{{ __('Contraseña') }} <span class="text-danger">*</span></label>
                        <input id="password" type="password" class="form-glass-input @error('password') border-danger @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">

                        @error('password')
                            <div class="form-glass-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        @if (Route::has('password.request'))
                            <a class="btn-link small" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-main w-100 justify-content-center py-3 fs-6">
                        <i class="bi bi-shield-lock me-2"></i> {{ __('Confirmar Contraseña') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
