@extends('layouts.admin')

@section('title', 'Gobierno Propio y Autoridades Tradicionales')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-people-fill"></i> Estructura Organizativa
            </span>
            <span class="text-muted small">&bull; Página Nosotros y Gobierno Propio</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Autoridades Tradicionales & Directivos</h2>
        <p class="text-muted small mb-0">
            Administra a los representantes de la Junta de Gobierno, Guardia Cimarrona y comités veredales presentados en la sección Nosotros.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.board.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-person-plus-fill me-1"></i> Registrar Autoridad / Directivo
        </a>
    </div>
</div>

<!-- FILTROS Y ESTADO -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.board.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por nombre, cargo, comité o vereda..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="committee" class="form-select">
                    <option value="">Todos los comités...</option>
                    <option value="Junta de Gobierno" {{ $committee === 'Junta de Gobierno' ? 'selected' : '' }}>Junta de Gobierno</option>
                    <option value="Guardia Cimarrona" {{ $committee === 'Guardia Cimarrona' ? 'selected' : '' }}>Guardia Cimarrona</option>
                    <option value="Comité Ambiental" {{ $committee === 'Comité Ambiental' ? 'selected' : '' }}>Comité Ambiental y Cuencas</option>
                    <option value="Comité de Cultura" {{ $committee === 'Comité de Cultura' ? 'selected' : '' }}>Comité de Cultura y Saberes</option>
                    <option value="Comité de Mujeres" {{ $committee === 'Comité de Mujeres' ? 'selected' : '' }}>Comité de Mujeres y Familia</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Visibles en portal</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Ocultos</option>
                    <option value="trashed" {{ $status === 'trashed' ? 'selected' : '' }}>Papelera (SoftDelete)</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $status !== 'all' || $committee)
                    <a href="{{ route('admin.board.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE DIRECTIVOS -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-person-badge text-primary"></i>
            Autoridades y Líderes Registrados ({{ $members->total() }})
        </h3>
        <span class="text-muted small">
            <i class="bi bi-shield-check text-success me-1"></i>Conservación histórica activa (SoftDelete)
        </span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Orden</th>
                        <th style="min-width: 230px;">Nombre y Cargo</th>
                        <th>Comité / Instancia</th>
                        <th>Vereda</th>
                        <th>Contacto</th>
                        <th>Estado Portal</th>
                        <th class="text-end" style="min-width: 170px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $m)
                        <tr class="{{ $m->trashed() ? 'bg-light opacity-75' : '' }}">
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-dark font-monospace">#{{ $m->sort_order }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle overflow-hidden bg-secondary-subtle flex-shrink-0 d-flex align-items-center justify-content-center text-muted" style="width: 46px; height: 46px;">
                                        @if($m->photo_url)
                                            <img src="{{ $m->photo_url }}" alt="{{ $m->name }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <i class="bi bi-person-fill fs-4"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $m->name }}</div>
                                        <div class="small text-muted">{{ $m->role_title }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border" style="font-size: 0.72rem;">
                                    {{ $m->committee }}
                                </span>
                            </td>
                            <td>
                                <span class="small text-dark">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $m->vereda ?? 'Zanjón de Garrapatero' }}
                                </span>
                            </td>
                            <td>
                                <div class="small">
                                    @if($m->email)
                                        <div class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $m->email }}</div>
                                    @endif
                                    @if($m->phone)
                                        <div class="text-secondary"><i class="bi bi-telephone me-1"></i>{{ $m->phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($m->trashed())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-archive-fill me-1"></i>Inactivo (Papelera)
                                    </span>
                                @elseif($m->is_visible)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-eye-fill me-1"></i>Visible
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-eye-slash-fill me-1"></i>Oculto
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    @if(!$m->trashed())
                                        <!-- Botón Visibilidad -->
                                        <form action="{{ route('admin.board.toggle-visibility', $m->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $m->is_visible ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $m->is_visible ? 'Ocultar del organigrama' : 'Hacer visible' }}">
                                                <i class="bi {{ $m->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Botón Editar -->
                                        <a href="{{ route('admin.board.edit', $m->id) }}" class="btn btn-sm btn-outline-primary" title="Editar autoridad">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Botón Inactivar (Soft Delete) -->
                                        <form action="{{ route('admin.board.destroy', $m) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-title="¿Inactivar directivo?" data-confirm-text="La autoridad '{{ $m->name }}' pasará al archivo histórico mediante SoftDelete. Podrás restaurarla en cualquier momento.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Inactivar directivo">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botón Restaurar -->
                                        <form action="{{ route('admin.board.restore', $m->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Restaurar directivo">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2"></i>
                                No se encontraron autoridades registradas con los criterios seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $members->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
