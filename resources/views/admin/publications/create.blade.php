@extends('layouts.admin')

@section('title', 'Crear Nueva Publicación')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.publications.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver a Publicaciones
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Redactar Noticia o Comunicado Territorial</h2>
            <p class="text-muted small mb-0">Esta información alimentará la sección de Actualidad del portal público.</p>
        </div>
    </div>

    <form action="{{ route('admin.publications.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 1. INFORMACIÓN Y TEXTO --}}
        <x-admin.form-card title="Contenido de la Publicación" icon="bi-newspaper" kicker="01 · Redacción & Categoría">
            <div class="row g-3">
                <div class="col-md-8">
                    <x-admin.input name="title" label="Título del Artículo" icon="bi-type-h1" placeholder="Ej. Convocatoria a la Gran Asamblea Extraordinaria de Comuneros" required />
                </div>
                <div class="col-md-4">
                    <x-admin.select name="category" label="Categoría" icon="bi-tag" required>
                        <option value="Comunicado" {{ old('category') == 'Comunicado' ? 'selected' : '' }}>Comunicado Oficial</option>
                        <option value="Noticia" {{ old('category') == 'Noticia' ? 'selected' : '' }}>Noticia Comunitaria</option>
                        <option value="Asamblea" {{ old('category') == 'Asamblea' ? 'selected' : '' }}>Asamblea & Decisiones</option>
                        <option value="Territorio" {{ old('category') == 'Territorio' ? 'selected' : '' }}>Territorio y Veredas</option>
                        <option value="Cultura" {{ old('category') == 'Cultura' ? 'selected' : '' }}>Cultura e Identidad Afro</option>
                    </x-admin.select>
                </div>
                <div class="col-12">
                    <x-admin.input name="summary" label="Resumen Ejecutivo (Bajada)" icon="bi-blockquote-left" placeholder="Breve introducción para las tarjetas destacadas del portal (máx 300 caracteres)" />
                </div>
                <div class="col-12">
                    <x-admin.textarea name="content" label="Cuerpo Completo del Artículo" placeholder="Desarrollo completo de la noticia, acuerdos, declaraciones comunitarias..." rows="8" required />
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. MULTIMEDIA Y ANEXOS --}}
        <x-admin.form-card title="Multimedia, Anexos y Visibilidad" icon="bi-paperclip" kicker="02 · Archivos & Difusión">
            <div class="row g-3">
                <div class="col-md-7">
                    <x-admin.dropzone name="image_file" label="Imagen de Portada" accept="image/*" badge="Optimización WebP automática • Máx 10 MB" />
                </div>
                <div class="col-md-5">
                    <x-admin.input name="image_url" label="O bien, URL de imagen externa" icon="bi-link-45deg" placeholder="https://ejemplo.com/imagenes/asamblea.jpg" />
                </div>
                <div class="col-12">
                    <x-admin.dropzone name="attachment_file" label="Documento Anexo en PDF o Word (Opcional)" accept=".pdf,.doc,.docx,.xls,.xlsx,.zip" badge="Resolución, acta o comunicado oficial • Máx 20 MB" />
                </div>
                <div class="col-12 mt-2">
                    <x-admin.switch name="is_visible" label="Publicar Inmediatamente en Actualidad" description="El artículo será visible de forma instantánea para la comunidad en la página de inicio." :checked="true" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.publications.index') }}" submitText="Guardar y Publicar" icon="bi-send" />
    </form>
</div>
@endsection
