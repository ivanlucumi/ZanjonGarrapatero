@extends('layouts.admin')

@section('title', 'Registrar Multimedia (Foto o Video)')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.media.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver a Multimedia
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Registrar Fotografía o Video Comunitario</h2>
            <p class="text-muted small mb-0">Alimenta las secciones de Galería fotográfica y Videos territoriales del portal oficial.</p>
        </div>
    </div>

    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 1. DEFINICIÓN Y CLASIFICACIÓN --}}
        <x-admin.form-card title="Clasificación del Medio" icon="bi-collection-play" kicker="01 · Tipo & Categoría">
            <div class="row g-3">
                <div class="col-md-8">
                    <x-admin.input name="title" label="Título del Contenido" icon="bi-type" placeholder="Ej. Cosecha Tradicional de Cacao en la Vereda El Zanjón" required />
                </div>
                <div class="col-md-4">
                    <x-admin.select name="type" label="Tipo de Medio" icon="bi-camera-video" required>
                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Fotografía (Galería)</option>
                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video Territorial (YouTube/MP4)</option>
                    </x-admin.select>
                </div>
                <div class="col-md-6">
                    <x-admin.select name="category" label="Categoría Temática" icon="bi-folder" required>
                        <option value="Territorio" {{ old('category') == 'Territorio' ? 'selected' : '' }}>Territorio y Paisajes</option>
                        <option value="Cultura" {{ old('category') == 'Cultura' ? 'selected' : '' }}>Cultura, Danza y Música</option>
                        <option value="Comunidad" {{ old('category') == 'Comunidad' ? 'selected' : '' }}>Vida Comunitaria y Familias</option>
                        <option value="Asambleas" {{ old('category') == 'Asambleas' ? 'selected' : '' }}>Asambleas y Encuentros</option>
                        <option value="Producción" {{ old('category') == 'Producción' ? 'selected' : '' }}>Agricultura y Saberes</option>
                    </x-admin.select>
                </div>
                <div class="col-md-6">
                    <x-admin.input name="duration" label="Duración (Videos)" icon="bi-stopwatch" placeholder="Ej. 4:15 min" />
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. ARCHIVOS MULTIMEDIA --}}
        <x-admin.form-card title="Archivo o Enlace de Transmisión" icon="bi-cloud-arrow-up" kicker="02 · Carga & Enlace">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-admin.dropzone name="image_file" label="Subir Fotografía (WebP Automático)" accept="image/*" badge="Nitidez optimizada • Máx 10 MB" />
                </div>
                <div class="col-md-6">
                    <x-admin.dropzone name="video_file" label="Subir Video Directo (MP4 / WebM)" accept="video/mp4,video/webm,video/ogg,video/quicktime" badge="Límite estricto: 50 MB" />
                </div>
                <div class="col-12">
                    <x-admin.input name="file_url" label="O bien, enlace URL externo (YouTube embed o CDN)" icon="bi-link-45deg" placeholder="https://www.youtube.com/embed/... o https://ejemplo.com/recurso.mp4" />
                </div>
                <div class="col-md-7">
                    <x-admin.dropzone name="thumbnail_file" label="Portada / Miniatura (Para videos)" accept="image/*" badge="Carátula previa del reproductor • WebP auto" />
                </div>
                <div class="col-md-5">
                    <x-admin.input name="thumbnail_url" label="O enlace URL de miniatura" icon="bi-link-45deg" placeholder="https://ejemplo.com/miniaturas/portada.jpg" />
                </div>
                <div class="col-12">
                    <x-admin.textarea name="caption" label="Pie de Foto / Reseña Descriptiva" placeholder="Contexto de la toma, autor de la fotografía o testimonio comunitario..." rows="3" />
                </div>
            </div>
        </x-admin.form-card>

        {{-- 3. VISIBILIDAD --}}
        <x-admin.form-card title="Publicación y Destacados" icon="bi-eye" kicker="03 · Difusión">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-admin.switch name="is_visible" label="Visible en el Portal Público" description="Aparecerá en la galería o videoteca para los comuneros y visitantes." :checked="true" />
                </div>
                <div class="col-md-6">
                    <x-admin.switch name="is_featured" label="Destacar en Portada" description="Presentar en la sección de destacados o carrusel inicial del sitio." :checked="false" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.media.index') }}" submitText="Guardar Multimedia" icon="bi-cloud-check" />
    </form>
</div>
@endsection
