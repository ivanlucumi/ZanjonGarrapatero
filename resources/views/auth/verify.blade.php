@extends('layouts.app')

@section('content')
<div class="wrap">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="glass-card p-4 p-md-5 text-center">
                <img src="{{ asset('logo/logo.webp') }}" alt="Logo Consejo Comunitario Zanjón de Garrapatero" width="56" height="56" class="brand-mark mb-3">
                <h1 class="serif h3 mb-2" style="color: var(--brand-primary);">{{ __('Verifica tu Correo Electrónico') }}</h1>
                <p class="small mb-4" style="color: var(--text-muted);">
                    {{ __('Antes de continuar, por favor revisa tu bandeja de entrada en busca del enlace de verificación institucional.') }}
                </p>

                @if (session('resent'))
                    <div class="alert alert-glass-success mb-4 d-flex align-items-center justify-content-center gap-2" role="alert">
                        <i class="bi bi-check-circle-fill" style="color: var(--brand-accent);"></i>
                        <div>{{ __('Se ha enviado un nuevo enlace de verificación a tu correo.') }}</div>
                    </div>
                @endif

                <div class="p-4 rounded-4 mb-4" style="background: rgba(23, 45, 77, 0.05); border: 1px solid var(--line);">
                    <p class="small mb-2" style="color: var(--text-muted);">{{ __('¿No recibiste el correo de confirmación?') }}</p>
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-main">
                            <i class="bi bi-arrow-repeat me-1"></i> {{ __('Solicitar otro enlace') }}
                        </button>
                    </form>
                </div>

                <a href="{{ route('index') }}" class="btn-link small">
                    <i class="bi bi-house-door me-1"></i> Volver a la portada
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
