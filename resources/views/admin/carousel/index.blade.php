@extends('layouts.admin')

@section('title', 'Gestión del Carrusel Principal (Hero Slides)')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-sliders"></i> Portada Principal
            </span>
            <span class="text-muted small">&bull; Proyección Multimedia del Territorio</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Carrusel y Proyecciones de Portada</h2>
        <p class="text-muted small mb-0">
            Administra las diapositivas multimedia (fotografía, video o editorial) proyectadas en el inicio del portal público.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.carousel.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-plus-circle me-1"></i> Nueva Diapositiva
        </a>
    </div>
</div>

<!-- FILTROS Y ESTADO -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.carousel.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por título, subtítulo o etiqueta..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="type" class="form-select">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Todos los tipos de slide</option>
                    <option value="image" {{ $type === 'image' ? 'selected' : '' }}>Foto / Imagen</option>
                    <option value="video" {{ $type === 'video' ? 'selected' : '' }}>Video en Proyección</option>
                    <option value="none" {{ $type === 'none' ? 'selected' : '' }}>Editorial (Solo texto)</option>
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Visibles en portada</option>
                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Ocultos</option>
                    <option value="trashed" {{ $status === 'trashed' ? 'selected' : '' }}>Papelera (SoftDelete)</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $status !== 'all' || $type !== 'all')
                    <a href="{{ route('admin.carousel.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE SLIDES -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-images text-primary"></i>
            Diapositivas Configuradas ({{ $slides->total() }})
        </h3>
        <span class="text-muted small">
            <i class="bi bi-shield-check text-success me-1"></i>Protección histórica activa (SoftDelete)
        </span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Orden</th>
                        <th style="min-width: 250px;">Proyección y Título</th>
                        <th>Tipo</th>
                        <th>Botones de Acción</th>
                        <th>Estado Portada</th>
                        <th class="text-end" style="min-width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($slides as $slide)
                        <tr class="{{ $slide->trashed() ? 'bg-light opacity-75' : '' }}">
                            <td class="text-center">
                                <span class="badge bg-secondary-subtle text-dark font-monospace" style="font-size: 0.8rem;">
                                    #{{ $slide->sort_order }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 overflow-hidden bg-dark position-relative flex-shrink-0" style="width: 65px; height: 50px;">
                                        @if($slide->isImage() && $slide->media_url)
                                            <img src="{{ $slide->media_url }}" alt="{{ $slide->title }}" class="w-100 h-100 object-fit-cover">
                                        @elseif($slide->isVideo())
                                            <img src="{{ $slide->poster_url ?: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=300&q=80' }}" class="w-100 h-100 object-fit-cover opacity-75">
                                            <div class="position-absolute top-50 start-50 translate-middle text-white">
                                                <i class="bi bi-play-circle-fill fs-6"></i>
                                            </div>
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-white-50" style="background: var(--brand-primary);">
                                                <i class="bi bi-chat-square-text"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 320px;" title="{{ $slide->title }}">{{ $slide->title }}</div>
                                        <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.68rem;">
                                            {{ $slide->badge_text ?? 'Proyección Institucional' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($slide->isImage())
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                        <i class="bi bi-card-image me-1"></i>Fotografía
                                    </span>
                                @elseif($slide->isVideo())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        <i class="bi bi-camera-reels me-1"></i>Video
                                    </span>
                                @else
                                    <span class="badge bg-info-subtle text-info border border-info-subtle">
                                        <i class="bi bi-file-earmark-text me-1"></i>Editorial
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-dark fw-semibold">{{ $slide->primary_button_text ?? 'Sin botón' }}</span>
                                    @if($slide->secondary_button_text)
                                        <span class="text-muted"> / {{ $slide->secondary_button_text }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($slide->trashed())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-archive-fill me-1"></i>Inactiva (Papelera)
                                    </span>
                                @elseif($slide->is_visible)
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
                                    @if(!$slide->trashed())
                                        <!-- Botón Visibilidad -->
                                        <form action="{{ route('admin.carousel.toggle-visibility', $slide->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm {{ $slide->is_visible ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $slide->is_visible ? 'Ocultar diapositiva' : 'Hacer visible' }}">
                                                <i class="bi {{ $slide->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
                                            </button>
                                        </form>

                                        <!-- Botón Editar -->
                                        <a href="{{ route('admin.carousel.edit', $slide->id) }}" class="btn btn-sm btn-outline-primary" title="Editar diapositiva">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Botón Inactivar (Soft Delete) -->
                                        <form action="{{ route('admin.carousel.destroy', $slide) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-title="¿Inactivar diapositiva?" data-confirm-text="La diapositiva '{{ $slide->title }}' se archivará mediante SoftDelete y dejará de mostrarse en la portada.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Inactivar con SoftDelete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botón Restaurar -->
                                        <form action="{{ route('admin.carousel.restore', $slide->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Restaurar diapositiva desde papelera">
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
                                <i class="bi bi-sliders fs-1 d-block mb-2"></i>
                                No se encontraron diapositivas configuradas con los criterios seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($slides->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $slides->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
