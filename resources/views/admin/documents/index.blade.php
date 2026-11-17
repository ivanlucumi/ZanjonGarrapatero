@extends('layouts.admin')

@section('title', 'Gestión de Documentos y Biblioteca Institucional')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-file-earmark-arrow-up"></i> Repositorio Oficial
            </span>
            <span class="text-muted small">&bull; Normativa, Actas y Cartografía</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Documentos Institucionales</h2>
        <p class="text-muted small mb-0">
            Administra, sube, edita e inactiva los documentos normativos, censos, planes ambientales y actas del Consejo Comunitario.
        </p>
    </div>
    <div>
        <a href="{{ route('admin.documents.create') }}" class="btn btn-admin-primary">
            <i class="bi bi-cloud-arrow-up me-1"></i> Cargar Documento
        </a>
    </div>
</div>

<!-- FILTROS Y BÚSQUEDA -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.documents.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por título, archivo o descripción..." value="{{ $currentSearch }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="category" class="form-select">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $currentCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <select name="status" class="form-select">
                    <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Activos</option>
                    <option value="inactive" {{ $currentStatus === 'inactive' ? 'selected' : '' }}>Inactivados (Papelera)</option>
                    <option value="all" {{ $currentStatus === 'all' ? 'selected' : '' }}>Todos los registros</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($currentSearch || $currentCategory || $currentStatus !== 'active')
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE DOCUMENTOS -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-files text-primary"></i>
            Archivos en Repositorio ({{ $documents->total() }})
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
                        <th style="min-width: 280px;">Documento / Título</th>
                        <th>Categoría</th>
                        <th>Tamaño / Formato</th>
                        <th>Descargas</th>
                        <th>Visibilidad</th>
                        <th>Estado</th>
                        <th class="text-end" style="min-width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                        <tr class="{{ $doc->trashed() ? 'opacity-75 bg-light' : '' }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-3 p-2 bg-light border d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 1.4rem;">
                                        <i class="bi {{ $doc->fileIcon() }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $doc->title }}</div>
                                        <span class="text-muted small d-inline-block text-truncate" style="max-width: 260px;">
                                            <i class="bi bi-paperclip me-1"></i>{{ $doc->file_name }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $doc->category }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium text-dark">{{ $doc->formattedSize() }}</span>
                                <div class="text-muted small">{{ strtoupper(pathinfo($doc->file_name, PATHINFO_EXTENSION)) }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info border border-info-subtle">
                                    <i class="bi bi-download me-1"></i>{{ $doc->download_count }}
                                </span>
                            </td>
                            <td>
                                @if(! $doc->trashed())
                                    <form method="POST" action="{{ route('admin.documents.toggle-visibility', $doc->id) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $doc->is_visible ? 'btn-outline-success' : 'btn-outline-secondary' }}" style="padding: 2px 10px; font-size: 0.78rem;" title="Clic para alternar visibilidad">
                                            @if($doc->is_visible)
                                                <i class="bi bi-eye-fill me-1"></i> Visible
                                            @else
                                                <i class="bi bi-eye-slash me-1"></i> Oculto
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small"><i class="bi bi-dash-circle"></i> No aplica</span>
                                @endif
                            </td>
                            <td>
                                @if($doc->trashed())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        <i class="bi bi-archive-fill me-1"></i> Inactivado
                                    </span>
                                @elseif($doc->is_visible)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        Activo
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                        Borrador
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.documents.download', $doc->id) }}" class="btn btn-outline-primary" title="Descargar archivo">
                                        <i class="bi bi-download"></i>
                                    </a>
                                    @if(! $doc->trashed())
                                        <a href="{{ route('admin.documents.edit', $doc->id) }}" class="btn btn-outline-secondary" title="Editar detalles o reemplazar archivo">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.documents.destroy', $doc->id) }}" class="d-inline js-confirm-delete" data-confirm-title="¿Inactivar documento?" data-confirm-text="El documento '{{ $doc->title }}' se resguardará en el archivo histórico mediante SoftDelete. Podrás restaurarlo en cualquier momento.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Inactivar documento (SoftDelete)">
                                                <i class="bi bi-archive"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.documents.restore', $doc->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-success" title="Restaurar a estado activo">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restaurar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted mb-3">
                                    <i class="bi bi-folder-x display-4 text-secondary opacity-50"></i>
                                </div>
                                <h5 class="fw-bold text-dark">No se encontraron documentos</h5>
                                <p class="text-muted small mb-3">
                                    @if($currentSearch || $currentCategory || $currentStatus !== 'active')
                                        No hay documentos que coincidan con los filtros aplicados.
                                    @else
                                        Aún no hay documentos institucionales registrados en el repositorio.
                                    @endif
                                </p>
                                <a href="{{ route('admin.documents.create') }}" class="btn btn-sm btn-admin-primary">
                                    <i class="bi bi-cloud-arrow-up me-1"></i> Subir primer documento
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($documents->hasPages())
        <div class="admin-card-footer px-3 py-2 border-top">
            {{ $documents->links() }}
        </div>
    @endif
</div>
@endsection
