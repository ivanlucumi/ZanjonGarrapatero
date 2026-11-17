@extends('layouts.admin')

@section('title', 'Gestión de Territorios y Veredas')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-map-fill"></i> Geografía Ancestral
            </span>
            <span class="text-muted small">&bull; Mapeo Territorial del Consejo</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Veredas, Asentamientos y Cuencas Hídricas</h2>
        <p class="text-muted small mb-0">
            Administra la información de cada una de las veredas y sectores que componen el Consejo Comunitario.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.territories.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva Vereda / Sector
        </a>
    </div>
</div>

<!-- FILTROS Y ESTADO -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.territories.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por nombre de vereda, cuenca o actividades..." value="{{ $search }}">
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
            <div class="col-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $status !== 'all')
                    <a href="{{ route('admin.territories.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE VEREDAS -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-geo-alt text-primary"></i>
            Veredas y Sectores Registrados ({{ $territories->total() }})
        </h3>
        <span class="text-muted small">
            <i class="bi bi-shield-check text-success me-1"></i>Resguardo histórico con SoftDelete
        </span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Orden</th>
                        <th style="min-width: 200px;">Nombre y Sector</th>
                        <th>Familias Estimadas</th>
                        <th>Cuencas y Afluentes</th>
                        <th>Estado Portal</th>
                        <th class="text-end" style="min-width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($territories as $t)
                        <tr class="{{ $t->trashed() ? 'bg-light opacity-75' : '' }}">
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-dark font-monospace">#{{ $t->sort_order }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $t->name }}</div>
                                @if($t->sector_badge)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.68rem;">
                                        {{ $t->sector_badge }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($t->estimated_families)
                                    <span class="fw-semibold text-dark"><i class="bi bi-people-fill text-muted me-1"></i>{{ number_format($t->estimated_families) }} familias</span>
                                @else
                                    <span class="text-muted small">Por censar</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-dark text-truncate" style="max-width: 250px;">
                                    <i class="bi bi-water text-info me-1"></i>{{ $t->watersheds ?? 'Río Zanjón' }}
                                </div>
                            </td>
                            <td>
                                @if($t->trashed())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-archive-fill me-1"></i>Inactiva (Papelera)
                                    </span>
                                @elseif($t->is_visible)
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
                                    @if(!$t->trashed())
                                        <!-- Botón Visibilidad -->
                                        <form action="{{ route('admin.territories.toggle-visibility', $t->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $t->is_visible ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $t->is_visible ? 'Ocultar del mapa' : 'Hacer visible' }}">
                                                <i class="bi {{ $t->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Botón Editar -->
                                        <a href="{{ route('admin.territories.edit', $t->id) }}" class="btn btn-sm btn-outline-primary" title="Editar vereda">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Botón Inactivar (Soft Delete) -->
                                        <form action="{{ route('admin.territories.destroy', $t) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-title="¿Inactivar vereda?" data-confirm-text="La vereda '{{ $t->name }}' pasará a histórico mediante SoftDelete. Podrás reactivarla cuando lo requieras.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Inactivar vereda">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botón Restaurar -->
                                        <form action="{{ route('admin.territories.restore', $t->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Restaurar vereda">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-map fs-1 d-block mb-2"></i>
                                No se encontraron veredas registradas con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($territories->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $territories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
