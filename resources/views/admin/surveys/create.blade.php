@extends('layouts.admin')

@section('title', 'Crear Nueva Encuesta Territorial')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <a href="{{ route('admin.surveys.index') }}" class="btn btn-sm btn-admin-outline mb-2">
                    <i class="bi bi-arrow-left"></i> Volver a Encuestas
                </a>
                <h2 class="h3 fw-bold text-dark mb-1">Diseño de Encuesta Comunitario</h2>
                <p class="text-muted small mb-0">Configuración de instrumento censal o consulta comunitaria para el Consejo.</p>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="bi bi-card-checklist text-warning"></i>
                    Información General de la Encuesta
                </h3>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.surveys.store') }}" method="POST" id="survey-form">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-8">
                            <label for="title" class="form-label fw-bold small text-dark">Título de la Encuesta <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control rounded-3" value="{{ old('title') }}" placeholder="Ej. Censo Demográfico y Vocación Productiva 2026" required>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="category" class="form-label fw-bold small text-dark">Categoría Temática <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select rounded-3" required>
                                <option value="Censo Territorial" {{ old('category') == 'Censo Territorial' ? 'selected' : '' }}>Censo Territorial</option>
                                <option value="Proyectos Productivos" {{ old('category') == 'Proyectos Productivos' ? 'selected' : '' }}>Proyectos Productivos</option>
                                <option value="Salud y Bienestar" {{ old('category') == 'Salud y Bienestar' ? 'selected' : '' }}>Salud y Bienestar</option>
                                <option value="Identidad y Cultura Afro" {{ old('category') == 'Identidad y Cultura Afro' ? 'selected' : '' }}>Identidad y Cultura Afro</option>
                                <option value="Medio Ambiente y Agua" {{ old('category') == 'Medio Ambiente y Agua' ? 'selected' : '' }}>Medio Ambiente y Agua</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="target_vereda" class="form-label fw-bold small text-dark">Población Objetivo / Vereda</label>
                            <select name="target_vereda" id="target_vereda" class="form-select rounded-3">
                                <option value="">Todo el Territorio (Todas las veredas)</option>
                                <option value="Zanjón de Garrapatero" {{ old('target_vereda') == 'Zanjón de Garrapatero' ? 'selected' : '' }}>Zanjón de Garrapatero</option>
                                <option value="San Antonio" {{ old('target_vereda') == 'San Antonio' ? 'selected' : '' }}>San Antonio</option>
                                <option value="Dominguillo" {{ old('target_vereda') == 'Dominguillo' ? 'selected' : '' }}>Dominguillo</option>
                                <option value="La Toma" {{ old('target_vereda') == 'La Toma' ? 'selected' : '' }}>La Toma</option>
                                <option value="El Palmar" {{ old('target_vereda') == 'El Palmar' ? 'selected' : '' }}>El Palmar</option>
                            </select>
                            <div class="form-text small">Deja en blanco para que aplique a todos los comuneros del Consejo.</div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label fw-bold small text-dark">Estado Inicial <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Activa (Comuneros pueden responder de inmediato)</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Borrador (Oculta para comuneros)</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Cerrada</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-bold small text-dark">Objetivo y Descripción</label>
                            <textarea name="description" id="description" rows="3" class="form-control rounded-3" placeholder="Explique a la comunidad el propósito de este censo o encuesta y la importancia de su participación.">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- SEPARADOR -->
                    <div class="relief-divider">
                        <div class="relief-divider-dot"></div>
                    </div>

                    <!-- GESTIÓN DINÁMICA DE PREGUNTAS -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="h5 fw-bold text-dark mb-0">
                            <i class="bi bi-ui-checks text-warning me-2"></i>
                            Cuestionario y Preguntas
                        </h4>
                        <button type="button" class="btn btn-sm btn-admin-outline" id="add-question-btn">
                            <i class="bi bi-plus-circle me-1"></i> Añadir Pregunta
                        </button>
                    </div>

                    <div id="questions-container">
                        <!-- Pregunta 1 por defecto -->
                        <div class="question-row p-3 rounded-4 border bg-light-subtle mb-3" data-index="0">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-primary">Pregunta 1</span>
                                <button type="button" class="btn btn-sm text-danger remove-question-btn" style="display: none;">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                            <input type="hidden" name="questions[0][id]" value="q_1">
                            <div class="row g-2">
                                <div class="col-12 col-md-7">
                                    <label class="form-label small fw-semibold">Enunciado de la Pregunta</label>
                                    <input type="text" name="questions[0][label]" class="form-control rounded-3" value="¿Cuántas personas conforman actualmente su núcleo familiar?" required>
                                </div>
                                <div class="col-12 col-md-5">
                                    <label class="form-label small fw-semibold">Tipo de Respuesta</label>
                                    <select name="questions[0][type]" class="form-select rounded-3 question-type-select">
                                        <option value="number" selected>Número entero</option>
                                        <option value="text">Texto breve</option>
                                        <option value="textarea">Texto largo / Detallado</option>
                                        <option value="radio">Selección única (Opciones)</option>
                                        <option value="select">Lista desplegable</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pregunta 2 por defecto -->
                        <div class="question-row p-3 rounded-4 border bg-light-subtle mb-3" data-index="1">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-primary">Pregunta 2</span>
                                <button type="button" class="btn btn-sm text-danger remove-question-btn">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                            <input type="hidden" name="questions[1][id]" value="q_2">
                            <div class="row g-2">
                                <div class="col-12 col-md-7">
                                    <label class="form-label small fw-semibold">Enunciado de la Pregunta</label>
                                    <input type="text" name="questions[1][label]" class="form-control rounded-3" value="¿Cuál es la principal actividad económica o productiva de su predio o familia?" required>
                                </div>
                                <div class="col-12 col-md-5">
                                    <label class="form-label small fw-semibold">Tipo de Respuesta</label>
                                    <select name="questions[1][type]" class="form-select rounded-3 question-type-select">
                                        <option value="text" selected>Texto breve</option>
                                        <option value="textarea">Texto largo / Detallado</option>
                                        <option value="number">Número entero</option>
                                        <option value="radio">Selección única (Opciones)</option>
                                        <option value="select">Lista desplegable</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Pregunta 3 por defecto -->
                        <div class="question-row p-3 rounded-4 border bg-light-subtle mb-3" data-index="2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="badge bg-primary">Pregunta 3</span>
                                <button type="button" class="btn btn-sm text-danger remove-question-btn">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                            <input type="hidden" name="questions[2][id]" value="q_3">
                            <div class="row g-2">
                                <div class="col-12 col-md-7">
                                    <label class="form-label small fw-semibold">Enunciado de la Pregunta</label>
                                    <input type="text" name="questions[2][label]" class="form-control rounded-3" value="¿Cuenta su vivienda con acceso a acueducto veredal permanente?" required>
                                </div>
                                <div class="col-12 col-md-5">
                                    <label class="form-label small fw-semibold">Tipo de Respuesta</label>
                                    <select name="questions[2][type]" class="form-select rounded-3 question-type-select">
                                        <option value="text">Texto breve</option>
                                        <option value="radio" selected>Selección única (Sí / No / Parcial)</option>
                                        <option value="textarea">Texto largo / Detallado</option>
                                        <option value="number">Número entero</option>
                                        <option value="select">Lista desplegable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end pt-4 border-top mt-4">
                        <a href="{{ route('admin.surveys.index') }}" class="btn btn-outline-secondary rounded-pill px-4 me-2">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-admin-gold px-4">
                            <i class="bi bi-check2-circle me-1"></i> Publicar y Guardar Encuesta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('questions-container');
        const addBtn = document.getElementById('add-question-btn');
        let questionCounter = 3;

        addBtn.addEventListener('click', function () {
            const index = questionCounter++;
            const div = document.createElement('div');
            div.className = 'question-row p-3 rounded-4 border bg-light-subtle mb-3';
            div.dataset.index = index;
            div.innerHTML = `
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="badge bg-primary">Pregunta ${index + 1}</span>
                    <button type="button" class="btn btn-sm text-danger remove-question-btn">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
                <input type="hidden" name="questions[${index}][id]" value="q_${index + 1}">
                <div class="row g-2">
                    <div class="col-12 col-md-7">
                        <label class="form-label small fw-semibold">Enunciado de la Pregunta</label>
                        <input type="text" name="questions[${index}][label]" class="form-control rounded-3" placeholder="Escriba aquí la pregunta comunitaria..." required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="form-label small fw-semibold">Tipo de Respuesta</label>
                        <select name="questions[${index}][type]" class="form-select rounded-3">
                            <option value="text">Texto breve</option>
                            <option value="textarea">Texto largo / Detallado</option>
                            <option value="number">Número entero</option>
                            <option value="radio">Selección única (Opciones)</option>
                        </select>
                    </div>
                </div>
            `;
            container.appendChild(div);
            attachRemoveEvents();
        });

        function attachRemoveEvents() {
            container.querySelectorAll('.remove-question-btn').forEach(btn => {
                btn.onclick = function () {
                    const rows = container.querySelectorAll('.question-row');
                    if (rows.length > 1) {
                        this.closest('.question-row').remove();
                    } else {
                        alert('La encuesta debe tener al menos una pregunta.');
                    }
                };
            });
        }

        attachRemoveEvents();
    });
</script>
@endpush
@endsection
