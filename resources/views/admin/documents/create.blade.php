@extends('layouts.admin')

@section('title', 'Cargar Nuevo Documento Institucional')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver al Repositorio
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Cargar Documento Institucional</h2>
            <p class="text-muted small mb-0">Publica normativas, acuerdos de asamblea, censos y planes ambientales con descarga rápida optimizada.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- 1. IDENTIFICACIÓN Y CATEGORÍA --}}
        <x-admin.form-card title="Clasificación Institucional" icon="bi-file-earmark-text" kicker="01 · Metadatos">
            <div class="row g-3">
                <div class="col-md-8">
                    <x-admin.input name="title" label="Título del Documento" icon="bi-type" placeholder="Ej: Estatuto de Gobierno Propio y Convivencia Veredal 2026" required />
                </div>
                <div class="col-md-4">
                    <x-admin.select name="category" label="Categoría Institucional" icon="bi-folder2-open" required>
                        <option value="">Selecciona categoría...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </x-admin.select>
                </div>
                <div class="col-12">
                    <x-admin.textarea name="description" label="Descripción o Síntesis Informativa" placeholder="Describe brevemente el alcance del documento, la asamblea que lo aprobó o las veredas a las que aplica..." rows="3" />
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. ARCHIVO Y VISIBILIDAD --}}
        <x-admin.form-card title="Archivo y Reglas de Publicación" icon="bi-cloud-arrow-up" kicker="02 · Adjunto & Visibilidad">
            <div class="row g-3">
                <div class="col-12">
                    <x-admin.dropzone name="document_file" label="Archivo del Documento (PDF, Word, Excel, ZIP)" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt" badge="Formatos estándar • Máx 20 MB por archivo" required />
                </div>
                <div class="col-12 mt-2">
                    <x-admin.switch name="is_visible" label="Publicar Inmediatamente en la Biblioteca Comunitaria" description="Permite que cualquier comunero o visitante consulte y descargue el documento desde el portal." :checked="true" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.documents.index') }}" submitText="Cargar Documento" icon="bi-cloud-arrow-up" />
    </form>
</div>
@endsection
