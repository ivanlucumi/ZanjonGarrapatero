@extends('layouts.admin')

@section('title', 'Editar Publicación')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.publications.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver a Publicaciones
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Editar: {{ $publication->title }}</h2>
            <p class="text-muted small mb-0">Toda modificación quedará registrada con fecha, usuario e IP en la bitácora de auditoría.</p>
        </div>
        @if($publication->trashed())
            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">
                <i class="bi bi-archive-fill me-1"></i> En papelera / Histórico (Inactivo)
            </span>
        @endif
    </div>

    <form action="{{ route('admin.publications.update', $publication->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. INFORMACIÓN Y TEXTO --}}
        <x-admin.form-card title="Contenido de la Publicación" icon="bi-newspaper" kicker="01 · Redacción & Categoría">
            <div class="row g-3">
                <div class="col-md-8">
                    <x-admin.input name="title" label="Título del Artículo" icon="bi-type-h1" :value="$publication->title" required />
                </div>
                <div class="col-md-4">
                    <x-admin.select name="category" label="Categoría" icon="bi-tag" required>
                        <option value="Comunicado" {{ old('category', $publication->category) == 'Comunicado' ? 'selected' : '' }}>Comunicado Oficial</option>
                        <option value="Noticia" {{ old('category', $publication->category) == 'Noticia' ? 'selected' : '' }}>Noticia Comunitaria</option>
                        <option value="Asamblea" {{ old('category', $publication->category) == 'Asamblea' ? 'selected' : '' }}>Asamblea & Decisiones</option>
                        <option value="Territorio" {{ old('category', $publication->category) == 'Territorio' ? 'selected' : '' }}>Territorio y Veredas</option>
                        <option value="Cultura" {{ old('category', $publication->category) == 'Cultura' ? 'selected' : '' }}>Cultura e Identidad Afro</option>
                    </x-admin.select>
                </div>
                <div class="col-12">
                    <x-admin.input name="summary" label="Resumen Ejecutivo (Bajada)" icon="bi-blockquote-left" :value="$publication->summary" />
                </div>
                <div class="col-12">
                    <x-admin.textarea name="content" label="Cuerpo Completo del Artículo" rows="8" required>{{ old('content', $publication->content) }}</x-admin.textarea>
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. MULTIMEDIA Y ANEXOS --}}
        <x-admin.form-card title="Multimedia, Anexos y Visibilidad" icon="bi-paperclip" kicker="02 · Archivos & Difusión">
            <div class="row g-3">
                <div class="col-md-7">
                    <x-admin.dropzone name="image_file" label="Reemplazar Imagen de Portada (Opcional)" accept="image/*" badge="Optimización WebP automática • Máx 10 MB" />
                </div>
                <div class="col-md-5">
                    <x-admin.input name="image_url" label="O bien, URL de imagen externa" icon="bi-link-45deg" :value="$publication->image_url" />
                    @if($publication->image_url)
                        <div class="small text-muted mt-1 text-truncate">
                            <span class="fw-semibold">Portada actual:</span> <code>{{ $publication->image_url }}</code>
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <x-admin.dropzone name="attachment_file" label="Reemplazar Documento Anexo (Opcional)" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" badge="Resolución, acta o anexo PDF • Máx 20 MB" />
                    @if($publication->attachment_url)
                        <div class="p-2 mt-2 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                            <span class="small text-dark fw-medium">
                                <i class="bi bi-paperclip me-1 text-primary"></i> Anexo actual: {{ $publication->attachment_name ?? basename($publication->attachment_url) }}
                            </span>
                            <a href="{{ $publication->attachment_url }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2 small">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Ver anexo
                            </a>
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <label for="active_from" class="form-label fw-bold small text-dark">Visible Desde (Fecha y Hora Opcional)</label>
                    <input type="datetime-local" name="active_from" id="active_from" class="form-control rounded-3" value="{{ old('active_from', $publication->active_from ? $publication->active_from->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="col-md-6">
                    <label for="active_until" class="form-label fw-bold small text-dark">Visible Hasta (Fecha y Hora Opcional)</label>
                    <input type="datetime-local" name="active_until" id="active_until" class="form-control rounded-3" value="{{ old('active_until', $publication->active_until ? $publication->active_until->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="col-12 mt-2">
                    <x-admin.switch name="is_visible" label="Publicar Inmediatamente en Actualidad" description="El artículo será visible dentro del rango de fechas activas configurado." :checked="$publication->is_visible" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.publications.index') }}" submitText="Guardar Cambios" icon="bi-check-lg" />
    </form>
</div>
@endsection
