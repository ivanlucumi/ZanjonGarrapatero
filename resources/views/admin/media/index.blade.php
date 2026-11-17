@extends('layouts.admin')

@section('title', 'Gestión Multimedia: Galería y Videos')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-collection-play"></i> Archivo Audiovisual
            </span>
            <span class="text-muted small">&bull; Memoria Territorial de Garrapatero</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Galería Fotográfica y Videos Comunitarios</h2>
        <p class="text-muted small mb-0">
            Control de fotos, registros de campo y videos territoriales. Resguardo seguro con SoftDeletes.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.media.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-cloud-arrow-up me-1"></i> Subir / Registrar Multimedia
        </a>
    </div>
</div>

<!-- FILTROS Y ESTADO -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.media.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por título, categoría o pie de foto..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="type" class="form-select">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Todos los tipos (Fotos y Videos)</option>
                    <option value="image" {{ $type === 'image' ? 'selected' : '' }}>Fotografías</option>
                    <option value="video" {{ $type === 'video' ? 'selected' : '' }}>Videos Comunitarios</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Visibles en portal</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Ocultos</option>
                    <option value="trashed" {{ $status === 'trashed' ? 'selected' : '' }}>Papelera (SoftDelete)</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $type !== 'all' || $status !== 'all')
                    <a href="{{ route('admin.media.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- GRILLA / LISTADO MULTIMEDIA -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-images text-success"></i>
            Elementos Audiovisuales Registrados ({{ $mediaItems->total() }})
        </h3>
        <span class="text-muted small">
            <i class="bi bi-shield-check text-success me-1"></i>Ningún registro es destruido (SoftDelete activo)
        </span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="min-width: 250px;">Contenido y Vista Previa</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Destacado</th>
                        <th>Visibilidad Portal</th>
                        <th class="text-end" style="min-width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mediaItems as $item)
                        <tr class="{{ $item->trashed() ? 'bg-light opacity-75' : '' }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative rounded-3 overflow-hidden bg-dark shadow-sm flex-shrink-0" style="width: 60px; height: 50px;">
                                        @if($item->isImage())
                                            <img src="{{ $item->file_url }}" alt="{{ $item->title }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <img src="{{ $item->thumbnail_url ?: 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=300&q=80' }}" alt="{{ $item->title }}" class="w-100 h-100 object-fit-cover opacity-75">
                                            <div class="position-absolute top-50 start-50 translate-middle text-white">
                                                <i class="bi bi-play-circle-fill fs-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 280px;">{{ $item->title }}</div>
                                        <div class="text-muted text-truncate" style="font-size: 0.75rem; max-width: 280px;">{{ $item->caption ?? 'Sin descripción adicional' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item->isImage())
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <i class="bi bi-camera me-1"></i>Fotografía
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        <i class="bi bi-film me-1"></i>Video ({{ $item->duration ?? 'Territorial' }})
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle" style="font-size: 0.72rem;">
                                    {{ $item->category }}
                                </span>
                            </td>
                            <td>
                                @if($item->is_featured)
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <i class="bi bi-star-fill text-warning me-1"></i>Portada
                                    </span>
                                @else
                                    <span class="text-muted small">Estándar</span>
                                @endif
                            </td>
                            <td>
                                @if($item->trashed())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-archive-fill me-1"></i>Inactivo (Papelera)
                                    </span>
                                @elseif($item->is_visible)
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
                                    @if(!$item->trashed())
                                        <!-- Botón Visibilidad -->
                                        <form action="{{ route('admin.media.toggle-visibility', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $item->is_visible ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $item->is_visible ? 'Ocultar del portal' : 'Hacer visible' }}">
                                                <i class="bi {{ $item->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Botón Editar -->
                                        <a href="{{ route('admin.media.edit', $item->id) }}" class="btn btn-sm btn-outline-primary" title="Editar contenido">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Botón Inactivar (Soft Delete) -->
                                        <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-title="¿Inactivar archivo multimedia?" data-confirm-text="El elemento '{{ $item->title }}' pasará a histórico con SoftDelete y dejará de proyectarse en el portal.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Inactivar con SoftDelete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botón Restaurar -->
                                        <form action="{{ route('admin.media.restore', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Restaurar desde papelera">
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
                                <i class="bi bi-camera-video-off fs-1 d-block mb-2"></i>
                                No se encontraron elementos multimedia con los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mediaItems->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $mediaItems->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
