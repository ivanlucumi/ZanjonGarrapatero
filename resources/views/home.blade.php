@extends('layouts.app')

@section('content')
<div class="wrap">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="glass-card p-4 p-lg-5 mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div>
                        <span class="glass-pill glass-pill-gold mb-2">Panel Institucional · Afiliación Activa</span>
                        <h1 class="serif h2 mb-1" style="color: var(--brand-primary);">Bienvenido, {{ Auth::user()->name }}</h1>
                        <p class="small mb-0" style="color: var(--text-muted);">
                            Plataforma comunitaria del Consejo Comunitario Zanjón de Garrapatero · Cauca, Colombia
                        </p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('index') }}" class="btn btn-main">
                            <i class="bi bi-house-door me-1"></i> Ir al Portal
                        </a>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-glass-success mb-4 d-flex align-items-center gap-3" role="alert">
                        <i class="bi bi-check-circle-fill fs-4" style="color: var(--brand-accent);"></i>
                        <div>{{ session('status') }}</div>
                    </div>
                @endif

                <div class="row g-4 mt-1">
                    <div class="col-md-4">
                        <div class="p-4 rounded-4 h-100" style="background: rgba(23, 45, 77, 0.05); border: 1px solid var(--line);">
                            <i class="bi bi-person-badge fs-2 mb-2 d-block" style="color: var(--brand-accent);"></i>
                            <strong class="d-block mb-1" style="color: var(--brand-primary);">Registro Étnico</strong>
                            <small class="d-block" style="color: var(--text-muted);">Padrón comunitario conforme a la Ley 70 de 1993.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 rounded-4 h-100" style="background: rgba(23, 45, 77, 0.05); border: 1px solid var(--line);">
                            <i class="bi bi-geo-alt fs-2 mb-2 d-block" style="color: var(--brand-primary);"></i>
                            <strong class="d-block mb-1" style="color: var(--brand-primary);">8 Veredas Conectadas</strong>
                            <small class="d-block" style="color: var(--text-muted);">Articulación con comités y líderes locales.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 rounded-4 h-100" style="background: rgba(23, 45, 77, 0.05); border: 1px solid var(--line);">
                            <i class="bi bi-shield-check fs-2 mb-2 d-block" style="color: var(--brand-accent);"></i>
                            <strong class="d-block mb-1" style="color: var(--brand-primary);">Guardia Cimarrona</strong>
                            <small class="d-block" style="color: var(--text-muted);">Custodia pacífica de cuencas y convivencia.</small>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-top d-flex justify-content-between align-items-center flex-wrap gap-3" style="border-color: var(--line) !important;">
                    <span class="small" style="color: var(--text-muted);">
                        Sesión iniciada con: <strong style="color: var(--brand-primary);">{{ Auth::user()->email }}</strong>
                    </span>
                    <a href="{{ route('contacto') }}" class="btn btn-ghost">
                        <i class="bi bi-chat-left-dots me-1"></i> Contactar Autoridad Propia
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
