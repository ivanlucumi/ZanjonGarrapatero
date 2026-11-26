@extends('layouts.admin')

@section('title', 'Programar Nueva Actividad')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-admin-outline mb-2">
                    <i class="bi bi-arrow-left"></i> Volver a Actividades
                </a>
                <h2 class="h3 fw-bold text-dark mb-1">Programar Nueva Actividad o Asamblea</h2>
                <p class="text-muted small mb-0">Esta convocatoria será visible para la comunidad en la agenda del portal.</p>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="bi bi-calendar-check text-warning"></i>
                    Información del Evento Comunitario
                </h3>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.activities.store') }}" method="POST">
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
                            <label for="title" class="form-label fw-bold small text-dark">Nombre de la Actividad <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control rounded-3" value="{{ old('title') }}" placeholder="Ej. Minga Comunitaria de Limpieza y Adecuación del Acueducto" required>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="category" class="form-label fw-bold small text-dark">Categoría <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select rounded-3" required>
                                <option value="Asamblea" {{ old('category') == 'Asamblea' ? 'selected' : '' }}>Asamblea General</option>
                                <option value="Minga" {{ old('category') == 'Minga' ? 'selected' : '' }}>Minga Comunitaria</option>
                                <option value="Taller" {{ old('category') == 'Taller' ? 'selected' : '' }}>Taller de Formación</option>
                                <option value="Cultura" {{ old('category') == 'Cultura' ? 'selected' : '' }}>Celebración Cultural Afro</option>
                                <option value="Reunión" {{ old('category') == 'Reunión' ? 'selected' : '' }}>Reunión de Junta</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="event_date" class="form-label fw-bold small text-dark">Fecha y Hora del Evento</label>
                            <input type="datetime-local" name="event_date" id="event_date" class="form-control rounded-3" value="{{ old('event_date') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="location" class="form-label fw-bold small text-dark">Lugar / Sede / Vereda</label>
                            <input type="text" name="location" id="location" class="form-control rounded-3" value="{{ old('location') }}" placeholder="Ej. Caseta Comunal Zanjón Central">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="active_from" class="form-label fw-bold small text-dark">Publicación Visible Desde (Opcional)</label>
                            <input type="datetime-local" name="active_from" id="active_from" class="form-control rounded-3" value="{{ old('active_from') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="active_until" class="form-label fw-bold small text-dark">Publicación Visible Hasta (Opcional)</label>
                            <input type="datetime-local" name="active_until" id="active_until" class="form-control rounded-3" value="{{ old('active_until') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label fw-bold small text-dark">Estado Inicial <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3" required>
                                <option value="programada" {{ old('status', 'programada') == 'programada' ? 'selected' : '' }}>Programada (Próxima)</option>
                                <option value="en_curso" {{ old('status') == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="finalizada" {{ old('status') == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                <option value="cancelada" {{ old('status') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 d-flex align-items-center mt-md-4 pt-md-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_visible" name="is_visible" value="1" {{ old('is_visible', '1') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="is_visible">
                                    Visible en portal público inmediatamente
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-bold small text-dark">Descripción y Objetivos <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="5" class="form-control rounded-3" placeholder="Detalles de los temas a tratar, recomendaciones de asistencia, herramientas requeridas..." required>{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-admin-primary">
                            <i class="bi bi-calendar-check me-1"></i> Guardar Actividad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
