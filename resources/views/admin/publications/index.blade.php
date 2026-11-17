@extends('layouts.admin')

@section('title', 'Gestión de Publicaciones y Noticias')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-newspaper"></i> CMS Territorial
            </span>
            <span class="text-muted small">&bull; Actualidad del Consejo Comunitario</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Noticias, Comunicados y Publicaciones</h2>
        <p class="text-muted small mb-0">
            Administra las publicaciones del portal público. Controla la visibilidad y resguarda el histórico con SoftDeletes.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.publications.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva Publicación
        </a>
    </div>
</div>

<!-- FILTROS Y ESTADO -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.publications.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por título, contenido o resumen..." value="{{ $search }}">
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
                    <option value="Comunicado" {{ $category === 'Comunicado' ? 'selected' : '' }}>Comunicado</option>
                    <option value="Noticia" {{ $category === 'Noticia' ? 'selected' : '' }}>Noticia</option>
                    <option value="Asamblea" {{ $category === 'Asamblea' ? 'selected' : '' }}>Asamblea</option>
                    <option value="Territorio" {{ $category === 'Territorio' ? 'selected' : '' }}>Territorio</option>
                    <option value="Cultura" {{ $category === 'Cultura' ? 'selected' : '' }}>Cultura</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $status !== 'all' || $category)
                    <a href="{{ route('admin.publications.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE PUBLICACIONES -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-file-earmark-text text-primary"></i>
            Listado de Artículos y Noticias ({{ $publications->total() }})
        </h3>
        <span class="text-muted small">
            <i class="bi bi-shield-check text-success me-1"></i>Política de no borrado permanente activa (SoftDelete)
        </span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="min-width: 250px;">Título y Categoría</th>
                        <th>Autor</th>
                        <th>Estado Portal</th>
                        <th>Fecha Publicación</th>
                        <th>Registro Histórico</th>
                        <th class="text-end" style="min-width: 220px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($publications as $pub)
                        <tr class="{{ $pub->trashed() ? 'bg-light opacity-75' : '' }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($pub->image_url)
                                        <img src="{{ $pub->image_url }}" alt="{{ $pub->title }}" class="rounded-3 object-fit-cover shadow-sm" style="width: 50px; height: 50px;">
                                    @else
                                        <div class="rounded-3 bg-secondary-subtle d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image fs-5"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 320px;">{{ $pub->title }}</div>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.7rem;">
                                            {{ $pub->category }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $pub->author->name ?? 'Equipo Comunitario' }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $pub->author->role?->label() ?? 'Editor' }}</div>
                            </td>
                            <td>
                                @if($pub->trashed())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-archive-fill me-1"></i>Inactivo (Papelera)
                                    </span>
                                @elseif($pub->is_visible)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-eye-fill me-1"></i>Visible al Público
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-eye-slash-fill me-1"></i>Oculto
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-dark">{{ $pub->published_at ? $pub->published_at->format('d/m/Y') : 'Borrador' }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $pub->created_at->diffForHumans() }}</div>
                            </td>
                            <td>
                                @if($pub->trashed())
                                    <span class="text-danger small" title="Inactivado el {{ $pub->deleted_at }}">
                                        <i class="bi bi-clock-history"></i> {{ $pub->deleted_at->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="text-success small">
                                        <i class="bi bi-check2-circle"></i> Activo en BD
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    @if(!$pub->trashed())
                                        <!-- Botón Visibilidad -->
                                        <form action="{{ route('admin.publications.toggle-visibility', $pub->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $pub->is_visible ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $pub->is_visible ? 'Ocultar del portal' : 'Hacer visible en portal' }}">
                                                <i class="bi {{ $pub->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Botón Editar -->
                                        <a href="{{ route('admin.publications.edit', $pub->id) }}" class="btn btn-sm btn-outline-primary" title="Editar contenido">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Botón Inactivar (Soft Delete) -->
                                        <form action="{{ route('admin.publications.destroy', $pub) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-title="¿Inactivar publicación?" data-confirm-text="La noticia '{{ $pub->title }}' pasará a histórico mediante SoftDelete y no se perderá su información.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Inactivar con SoftDelete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botón Restaurar -->
                                        <form action="{{ route('admin.publications.restore', $pub->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Restaurar publicación desde papelera">
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
                                <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                No se encontraron publicaciones registradas con los criterios seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($publications->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $publications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
