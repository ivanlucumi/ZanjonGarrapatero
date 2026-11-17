@extends('layouts.admin')

@section('title', 'Trazabilidad y Auditoría de Cambios')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-role badge-role-superadmin" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-shield-shaded"></i> Módulo Exclusivo
            </span>
            <span class="text-muted small">&bull; Super Administrador ("Ve Todo")</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Bitácora de Auditoría y Trazabilidad de Cambios</h2>
        <p class="text-muted small mb-0">
            Registro inmutable de todas las acciones efectuadas en la plataforma: creación, edición, cambios de visibilidad e inactivaciones.
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.audit.logins') }}" class="btn btn-admin-outline">
            <i class="bi bi-fingerprint me-1"></i> Ver Inicios de Sesión
        </a>
    </div>
</div>

<!-- FILTROS DE AUDITORÍA -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por usuario, descripción, IP o elemento..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="action" class="form-select">
                    <option value="">Todas las acciones...</option>
                    @foreach($actionTypes as $act)
                        <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>
                            {{ $act }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="entity_type" class="form-select">
                    <option value="">Todas las entidades...</option>
                    @foreach($entityTypes as $ent)
                        <option value="{{ $ent }}" {{ $entityType === $ent ? 'selected' : '' }}>
                            {{ $ent }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $action || $entityType)
                    <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE TRAZABILIDAD -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-clock-history text-primary"></i>
            Registros de Auditoría del Sistema ({{ $logs->total() }})
        </h3>
        <span class="text-muted small">
            <i class="bi bi-lock-fill text-warning me-1"></i>Bitácora append-only blindada
        </span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="min-width: 160px;">Fecha y Hora</th>
                        <th>Usuario y Rol</th>
                        <th>Acción Ejecutada</th>
                        <th>Entidad Afectada</th>
                        <th style="min-width: 250px;">Descripción del Cambio</th>
                        <th>Dirección IP / Origen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark small">
                                    <i class="bi bi-calendar3 me-1 text-muted"></i>{{ $log->created_at->format('d/m/Y') }}
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i>{{ $log->created_at->format('H:i:s') }} ({{ $log->created_at->diffForHumans() }})
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $log->user_name }}</div>
                                <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.7rem;">
                                    {{ $log->user_role }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $actionBadge = match(true) {
                                        str_contains($log->action, 'inactivar') => 'bg-danger-subtle text-danger border-danger-subtle',
                                        str_contains($log->action, 'crear') => 'bg-success-subtle text-success border-success-subtle',
                                        str_contains($log->action, 'actualizar') => 'bg-primary-subtle text-primary border-primary-subtle',
                                        str_contains($log->action, 'restaurar') => 'bg-info-subtle text-info border-info-subtle',
                                        str_contains($log->action, 'visibilidad') => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
                                        str_contains($log->action, 'login') => 'bg-secondary-subtle text-dark border-secondary-subtle',
                                        default => 'bg-light text-dark border'
                                    };
                                @endphp
                                <span class="badge border {{ $actionBadge }}" style="font-size: 0.72rem;">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td>
                                @if($log->entity_type)
                                    <div class="small fw-semibold text-dark">{{ $log->entity_type }}</div>
                                    @if($log->entity_title)
                                        <div class="text-muted text-truncate" style="font-size: 0.75rem; max-width: 180px;" title="{{ $log->entity_title }}">
                                            #{{ $log->entity_id }}: {{ $log->entity_title }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted small">Global</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-dark">{{ $log->description }}</div>
                                @if(!empty($log->properties))
                                    <details class="mt-1">
                                        <summary class="text-muted small" style="cursor: pointer; font-size: 0.72rem;">
                                            <i class="bi bi-code-slash me-1"></i>Ver metadatos / propiedades
                                        </summary>
                                        <pre class="bg-light p-2 rounded-2 mt-1 mb-0 border small text-dark" style="font-size: 0.7rem; max-width: 380px; overflow-x: auto;">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </details>
                                @endif
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">
                                    <i class="bi bi-hdd-network me-1 text-muted"></i>{{ $log->ip_address }}
                                </div>
                                <div class="text-muted text-truncate" style="font-size: 0.7rem; max-width: 140px;" title="{{ $log->user_agent }}">
                                    {{ $log->user_agent }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-shield-slash fs-1 d-block mb-2"></i>
                                No se encontraron registros de auditoría con los criterios especificados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
