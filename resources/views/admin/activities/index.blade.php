@extends('layouts.admin')

@section('title', 'Gestión de Actividades y Agenda')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-calendar-event"></i> Agenda Comunitaria
            </span>
            <span class="text-muted small">&bull; Asambleas, Mingas y Talleres</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Agenda y Encuentros del Territorio</h2>
        <p class="text-muted small mb-0">
            Control de eventos, fechas, ubicaciones y estado de visibilidad en el portal. Nada se borra: resguardo con SoftDeletes.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.activities.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-calendar-plus me-1"></i> Nueva Actividad
        </a>
    </div>
</div>

<!-- FILTROS Y ESTADO -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.activities.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por título, lugar o descripción..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Visibles en portal</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Ocultos</option>
                    <option value="trashed" {{ $status === 'trashed' ? 'selected' : '' }}>Papelera (SoftDelete)</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="category" class="form-select">
                    <option value="">Todas las categorías</option>
                    <option value="Asamblea" {{ $category === 'Asamblea' ? 'selected' : '' }}>Asamblea</option>
                    <option value="Minga" {{ $category === 'Minga' ? 'selected' : '' }}>Minga</option>
                    <option value="Taller" {{ $category === 'Taller' ? 'selected' : '' }}>Taller</option>
                    <option value="Cultura" {{ $category === 'Cultura' ? 'selected' : '' }}>Cultura</option>
                    <option value="Reunión" {{ $category === 'Reunión' ? 'selected' : '' }}>Reunión</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $status !== 'all' || $category)
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE ACTIVIDADES -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-calendar3 text-warning"></i>
            Actividades Comunitarias ({{ $activities->total() }})
        </h3>
        <span class="text-muted small">
            <i class="bi bi-shield-check text-success me-1"></i>Respaldo y trazabilidad total con SoftDelete
        </span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="min-width: 250px;">Actividad y Categoría</th>
                        <th>Fecha & Hora</th>
                        <th>Ubicación / Vereda</th>
                        <th>Estado Evento</th>
                        <th>Visibilidad Portal</th>
                        <th class="text-end" style="min-width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $act)
                        <tr class="{{ $act->trashed() ? 'bg-light opacity-75' : '' }}">
                            <td>
                                <div class="fw-bold text-dark">{{ $act->title }}</div>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.7rem;">
                                    {{ $act->category }}
                                </span>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">
                                    <i class="bi bi-clock me-1 text-muted"></i>{{ $act->event_date ? $act->event_date->format('d/m/Y H:i') : 'Fecha por definir' }}
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $act->location ?? 'Zanjón de Garrapatero' }}
                                </div>
                            </td>
                            <td>
                                @php
                                    $statusMap = [
                                        'programada' => ['class' => 'bg-info-subtle text-info-emphasis border-info-subtle', 'label' => 'Programada'],
                                        'en_curso' => ['class' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle', 'label' => 'En Curso'],
                                        'finalizada' => ['class' => 'bg-success-subtle text-success border-success-subtle', 'label' => 'Finalizada'],
                                        'cancelada' => ['class' => 'bg-danger-subtle text-danger border-danger-subtle', 'label' => 'Cancelada'],
                                    ];
                                    $st = $statusMap[$act->status] ?? ['class' => 'bg-secondary-subtle text-secondary', 'label' => $act->status];
                                @endphp
                                <span class="badge border {{ $st['class'] }}" style="font-size: 0.72rem;">
                                    {{ $st['label'] }}
                                </span>
                            </td>
                            <td>
                                @if($act->trashed())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-archive-fill me-1"></i>Inactiva (Papelera)
                                    </span>
                                @elseif($act->is_visible)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-eye-fill me-1"></i>Visible
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-eye-slash-fill me-1"></i>Oculta
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    @if(!$act->trashed())
                                        <!-- Botón Visibilidad -->
                                        <form action="{{ route('admin.activities.toggle-visibility', $act->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $act->is_visible ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $act->is_visible ? 'Ocultar del portal' : 'Hacer visible' }}">
                                                <i class="bi {{ $act->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Botón Editar -->
                                        <a href="{{ route('admin.activities.edit', $act->id) }}" class="btn btn-sm btn-outline-primary" title="Editar actividad">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Botón Inactivar (Soft Delete) -->
                                        <form action="{{ route('admin.activities.destroy', $act) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Confirmas inactivar esta actividad? Pasará a histórico mediante SoftDelete.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Inactivar con SoftDelete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botón Restaurar -->
                                        <form action="{{ route('admin.activities.restore', $act->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Restaurar actividad desde papelera">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restaurar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                No se encontraron actividades comunitarias registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
