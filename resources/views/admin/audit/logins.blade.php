@extends('layouts.admin')

@section('title', 'Historial de Inicios de Sesión')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-role badge-role-superadmin" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-shield-shaded"></i> Módulo Exclusivo
            </span>
            <span class="text-muted small">&bull; Super Administrador ("Ve Todo")</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Control de Acceso e Inicios de Sesión</h2>
        <p class="text-muted small mb-0">
            Registro detallado de accesos a la plataforma: quién ingresa, desde qué dirección IP, dispositivo y en qué momento.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.audit.index') }}" class="btn btn-admin-outline">
            <i class="bi bi-clock-history me-1"></i> Ver Trazabilidad de Cambios
        </a>
    </div>
</div>

<!-- FILTROS DE ACCESO -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.audit.logins') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por nombre, correo, IP o navegador..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="role" class="form-select">
                    <option value="">Todos los roles...</option>
                    <option value="Super Administrador" {{ $role === 'Super Administrador' ? 'selected' : '' }}>Super Administrador</option>
                    <option value="Administrador de Encuestas" {{ $role === 'Administrador de Encuestas' ? 'selected' : '' }}>Administrador de Encuestas</option>
                    <option value="Comunero / Usuario" {{ $role === 'Comunero / Usuario' ? 'selected' : '' }}>Comunero / Usuario</option>
                </select>
            </div>
            <div class="col-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $role)
                    <a href="{{ route('admin.audit.logins') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE LOGINS -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-fingerprint text-primary"></i>
            Historial de Inicios de Sesión ({{ $logins->total() }})
        </h3>
        <span class="text-muted small">
            <i class="bi bi-shield-check text-success me-1"></i>Captura automática de IP y User-Agent
        </span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="min-width: 170px;">Momento de Acceso</th>
                        <th>Usuario y Correo</th>
                        <th>Rol al Ingresar</th>
                        <th>Dirección IP</th>
                        <th>Navegador y Sistema Operativo</th>
                        <th>Estado de Conexión</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logins as $login)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark small">
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>{{ $login->login_at->format('d/m/Y') }}
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $login->login_at->format('H:i:s') }} ({{ $login->login_at->diffForHumans() }})
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $login->user_name }}</div>
                                <div class="small text-muted">{{ $login->user_email }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.72rem;">
                                    {{ $login->role }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border font-monospace">
                                    <i class="bi bi-hdd-network me-1 text-primary"></i>{{ $login->ip_address }}
                                </span>
                            </td>
                            <td>
                                <div class="small text-dark text-truncate" style="max-width: 280px;" title="{{ $login->user_agent }}">
                                    @php
                                        $ua = $login->user_agent;
                                        $icon = 'bi-laptop';
                                        if (str_contains(strtolower($ua), 'mobile') || str_contains(strtolower($ua), 'android') || str_contains(strtolower($ua), 'iphone')) {
                                            $icon = 'bi-phone';
                                        }
                                    @endphp
                                    <i class="bi {{ $icon }} me-1 text-muted"></i>{{ Str::limit($login->user_agent, 45) }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="bi bi-check-circle-fill me-1"></i>Autenticado
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                                No se registran inicios de sesión con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logins->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $logins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
