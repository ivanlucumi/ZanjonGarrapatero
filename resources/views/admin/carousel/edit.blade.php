@extends('layouts.admin')

@section('title', 'Editar Diapositiva del Carrusel')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.carousel.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver al Carrusel
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Editar: {{ $slide->title }}</h2>
            <p class="text-muted small mb-0">Toda modificación quedará registrada con fecha, usuario e IP en la bitácora de auditoría.</p>
        </div>
        @if($slide->trashed())
            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">
                <i class="bi bi-archive-fill me-1"></i> En papelera / Histórico (Inactivo)
            </span>
        @endif
    </div>

    <form action="{{ route('admin.carousel.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. CONTENIDO Y TEXTOS PRINCIPALES --}}
        <x-admin.form-card title="Contenido y Textos Principales" icon="bi-card-heading" kicker="01 · Portada & Titulares">
            <div class="row g-3">
                <div class="col-md-8">
                    <x-admin.input name="title" label="Título Principal (H1/H2)" icon="bi-type-h1" :value="$slide->title" required />
                </div>
                <div class="col-md-4">
                    <x-admin.select name="type" label="Tipo de Diapositiva" icon="bi-film" required>
                        <option value="image" {{ old('type', $slide->type) == 'image' ? 'selected' : '' }}>Foto / Imagen de Fondo</option>
                        <option value="video" {{ old('type', $slide->type) == 'video' ? 'selected' : '' }}>Video en Proyección</option>
                        <option value="none" {{ old('type', $slide->type) == 'none' ? 'selected' : '' }}>Editorial (Sin fondo)</option>
                    </x-admin.select>
                </div>
                <div class="col-md-8">
                    <x-admin.input name="badge_text" label="Texto de la Píldora Superior" icon="bi-tag" :value="$slide->badge_text" />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="sort_order" type="number" label="Orden de Aparición" icon="bi-sort-numeric-down" :value="$slide->sort_order" />
                </div>
                <div class="col-12">
                    <x-admin.textarea name="subtitle" label="Párrafo Descriptivo / Subtítulo" rows="3">{{ old('subtitle', $slide->subtitle) }}</x-admin.textarea>
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. RECURSOS MULTIMEDIA --}}
        <x-admin.form-card title="Recurso Multimedia y Proyección" icon="bi-camera-reels" kicker="02 · Medios & Formatos">
            <div class="row g-3">
                <div class="col-md-7">
                    <x-admin.dropzone name="media_file" label="Reemplazar Archivo Multimedia (Opcional)" accept="image/*,video/mp4,video/webm,video/ogg" badge="Imágenes WebP auto-optimizadas • Videos máx 50 MB" />
                </div>
                <div class="col-md-5">
                    <x-admin.input name="media_url" label="O enlace URL de recurso" icon="bi-link-45deg" :value="$slide->media_url" />
                    @if($slide->media_url)
                        <div class="small text-muted mt-1 text-truncate">
                            <span class="fw-semibold">Recurso actual:</span> <code>{{ $slide->media_url }}</code>
                        </div>
                    @endif
                    <x-admin.input name="projection_title" label="Título para Modal de Pantalla Completa" icon="bi-arrows-fullscreen" :value="$slide->projection_title" class="mt-2" />
                </div>
                <div class="col-md-7">
                    <x-admin.dropzone name="poster_file" label="Reemplazar Miniatura / Poster (Para videos)" accept="image/*" badge="Foto previa antes de reproducir • WebP optimizado" />
                </div>
                <div class="col-md-5">
                    <x-admin.input name="poster_url" label="O enlace URL de miniatura" icon="bi-link-45deg" :value="$slide->poster_url" />
                    @if($slide->poster_url)
                        <div class="small text-muted mt-1 text-truncate">
                            <span class="fw-semibold">Poster actual:</span> <code>{{ $slide->poster_url }}</code>
                        </div>
                    @endif
                </div>
            </div>
        </x-admin.form-card>

        {{-- 3. BOTONES Y VISIBILIDAD --}}
        <x-admin.form-card title="Botones de Enlace y Publicación" icon="bi-cursor" kicker="03 · Interacción & Estado">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-admin.input name="primary_button_text" label="Texto Botón Primario" icon="bi-box-arrow-up-right" :value="$slide->primary_button_text" />
                </div>
                <div class="col-md-6">
                    <x-admin.input name="primary_button_url" label="Ruta / Enlace Primario" icon="bi-link" :value="$slide->primary_button_url" />
                </div>
                <div class="col-md-6">
                    <x-admin.input name="secondary_button_text" label="Texto Botón Secundario (Opcional)" icon="bi-box-arrow-up-right" :value="$slide->secondary_button_text" />
                </div>
                <div class="col-md-6">
                    <x-admin.input name="secondary_button_url" label="Ruta / Enlace Secundario (Opcional)" icon="bi-link" :value="$slide->secondary_button_url" />
                </div>
                <div class="col-12 mt-2">
                    <x-admin.switch name="is_visible" label="Publicar Diapositiva Inmediatamente" description="Visible al público en el carrusel de portada de la página de inicio." :checked="$slide->is_visible" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.carousel.index') }}" submitText="Guardar Cambios" icon="bi-check-lg" />
    </form>
</div>
@endsection
