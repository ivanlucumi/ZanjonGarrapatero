@extends('layouts.admin')

@section('title', 'Editar Documento Institucional')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver al Repositorio
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Editar: {{ $document->title }}</h2>
            <p class="text-muted small mb-0">Toda modificación quedará registrada con fecha, usuario e IP en la bitácora de auditoría.</p>
        </div>
        @if($document->trashed())
            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">
                <i class="bi bi-archive-fill me-1"></i> En papelera / Histórico (Inactivo)
            </span>
        @endif
    </div>

    <form method="POST" action="{{ route('admin.documents.update', $document->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. IDENTIFICACIÓN Y CATEGORÍA --}}
        <x-admin.form-card title="Clasificación Institucional" icon="bi-file-earmark-text" kicker="01 · Metadatos">
            <div class="row g-3">
                <div class="col-md-8">
                    <x-admin.input name="title" label="Título del Documento" icon="bi-type" :value="$document->title" required />
                </div>
                <div class="col-md-4">
                    <x-admin.select name="category" label="Categoría Institucional" icon="bi-folder2-open" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $document->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </x-admin.select>
                </div>
                <div class="col-12">
                    <x-admin.textarea name="description" label="Descripción o Síntesis Informativa" rows="3">{{ old('description', $document->description) }}</x-admin.textarea>
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. ARCHIVO ACTUAL Y REEMPLAZO --}}
        <x-admin.form-card title="Archivo y Reglas de Publicación" icon="bi-cloud-arrow-up" kicker="02 · Adjunto & Visibilidad">
            <div class="row g-3">
                {{-- Resumen del archivo actual --}}
                <div class="col-12">
                    <div class="p-3 rounded-3 border bg-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="admin-form-icon-pill" style="margin: 0;">
                                <i class="bi {{ $document->fileIcon() }} fs-4 text-primary"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $document->file_name }}</div>
                                <div class="text-muted small">
                                    {{ $document->formattedSize() }} &bull; {{ $document->download_count }} descargas registradas
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.documents.download', $document->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download me-1"></i> Descargar Copia
                        </a>
                    </div>
                </div>

                <div class="col-12 mt-2">
                    <x-admin.dropzone name="document_file" label="Reemplazar Archivo (Opcional)" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt" badge="Conservará el actual si no seleccionas uno nuevo • Máx 20 MB" />
                </div>

                <div class="col-12 mt-2">
                    <x-admin.switch name="is_visible" label="Mantener Visible en la Biblioteca Comunitaria" description="Permite que cualquier comunero o visitante consulte y descargue el documento desde el portal." :checked="$document->is_visible" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.documents.index') }}" submitText="Guardar Cambios" icon="bi-check-lg" />
    </form>
</div>
@endsection
