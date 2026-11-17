@extends('layouts.admin')

@section('title', 'Editar Archivo Multimedia')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.media.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver a Multimedia
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Editar: {{ $item->title }}</h2>
            <p class="text-muted small mb-0">Toda modificación quedará registrada con fecha, usuario e IP en la bitácora de auditoría.</p>
        </div>
        @if($item->trashed())
            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">
                <i class="bi bi-archive-fill me-1"></i> En papelera / Histórico (Inactivo)
            </span>
        @endif
    </div>

    <form action="{{ route('admin.media.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. DEFINICIÓN Y CLASIFICACIÓN --}}
        <x-admin.form-card title="Clasificación del Medio" icon="bi-collection-play" kicker="01 · Tipo & Categoría">
            <div class="row g-3">
                <div class="col-md-8">
                    <x-admin.input name="title" label="Título del Contenido" icon="bi-type" :value="$item->title" required />
                </div>
                <div class="col-md-4">
                    <x-admin.select name="type" label="Tipo de Medio" icon="bi-camera-video" required>
                        <option value="image" {{ old('type', $item->type) == 'image' ? 'selected' : '' }}>Fotografía (Galería)</option>
                        <option value="video" {{ old('type', $item->type) == 'video' ? 'selected' : '' }}>Video Territorial (YouTube/MP4)</option>
                    </x-admin.select>
                </div>
                <div class="col-md-6">
                    <x-admin.select name="category" label="Categoría Temática" icon="bi-folder" required>
                        <option value="Territorio" {{ old('category', $item->category) == 'Territorio' ? 'selected' : '' }}>Territorio y Paisajes</option>
                        <option value="Cultura" {{ old('category', $item->category) == 'Cultura' ? 'selected' : '' }}>Cultura, Danza y Música</option>
                        <option value="Comunidad" {{ old('category', $item->category) == 'Comunidad' ? 'selected' : '' }}>Vida Comunitaria y Familias</option>
                        <option value="Asambleas" {{ old('category', $item->category) == 'Asambleas' ? 'selected' : '' }}>Asambleas y Encuentros</option>
                        <option value="Producción" {{ old('category', $item->category) == 'Producción' ? 'selected' : '' }}>Agricultura y Saberes</option>
                    </x-admin.select>
                </div>
                <div class="col-md-6">
                    <x-admin.input name="duration" label="Duración (Videos)" icon="bi-stopwatch" :value="$item->duration" placeholder="Ej. 4:15 min" />
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. ARCHIVOS MULTIMEDIA --}}
        <x-admin.form-card title="Archivo o Enlace de Transmisión" icon="bi-cloud-arrow-up" kicker="02 · Carga & Enlace">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-admin.dropzone name="image_file" label="Reemplazar Fotografía (Opcional)" accept="image/*" badge="Nitidez optimizada • Máx 10 MB" />
                </div>
                <div class="col-md-6">
                    <x-admin.dropzone name="video_file" label="Reemplazar Video Directo (Opcional)" accept="video/mp4,video/webm,video/ogg,video/quicktime" badge="Límite estricto: 50 MB" />
                </div>
                <div class="col-12">
                    <x-admin.input name="file_url" label="O bien, URL del Recurso (Imagen o Video)" icon="bi-link-45deg" :value="$item->file_url" />
                    @if($item->file_url)
                        <div class="small text-muted mt-1 text-truncate">
                            <span class="fw-semibold">Recurso actual:</span> <code>{{ $item->file_url }}</code>
                        </div>
                    @endif
                </div>
                <div class="col-md-7">
                    <x-admin.dropzone name="thumbnail_file" label="Reemplazar Portada / Miniatura (Opcional)" accept="image/*" badge="WebP optimizado" />
                </div>
                <div class="col-md-5">
                    <x-admin.input name="thumbnail_url" label="O bien, URL de Miniatura" icon="bi-link-45deg" :value="$item->thumbnail_url" />
                    @if($item->thumbnail_url)
                        <div class="small text-muted mt-1 text-truncate">
                            <span class="fw-semibold">Miniatura actual:</span> <code>{{ $item->thumbnail_url }}</code>
                        </div>
                    @endif
                </div>
                <div class="col-12">
                    <x-admin.textarea name="caption" label="Pie de Foto / Reseña Descriptiva" rows="3">{{ old('caption', $item->caption) }}</x-admin.textarea>
                </div>
            </div>
        </x-admin.form-card>

        {{-- 3. VISIBILIDAD --}}
        <x-admin.form-card title="Publicación y Destacados" icon="bi-eye" kicker="03 · Difusión">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-admin.switch name="is_visible" label="Visible en el Portal Público" description="Aparecerá en la galería o videoteca para los comuneros y visitantes." :checked="$item->is_visible" />
                </div>
                <div class="col-md-6">
                    <x-admin.switch name="is_featured" label="Destacar en Portada" description="Presentar en la sección de destacados o carrusel inicial del sitio." :checked="$item->is_featured" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.media.index') }}" submitText="Guardar Cambios" icon="bi-check-lg" />
    </form>
</div>
@endsection
