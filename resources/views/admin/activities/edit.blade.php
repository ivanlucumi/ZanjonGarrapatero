@extends('layouts.admin')

@section('title', 'Editar Actividad')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-admin-outline mb-2">
                    <i class="bi bi-arrow-left"></i> Volver a Actividades
                </a>
                <h2 class="h3 fw-bold text-dark mb-1">Editar Actividad Comunitaria</h2>
                <p class="text-muted small mb-0">Modifica datos o estado. Todos los cambios se registrarán en la bitácora de auditoría.</p>
            </div>
            @if($activity->trashed())
                <span class="badge bg-danger-subtle text-danger border border-danger p-2">
                    <i class="bi bi-archive-fill me-1"></i> Actividad en histórico (SoftDelete)
                </span>
            @endif
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="bi bi-calendar-event text-warning"></i>
                    Editar: {{ $activity->title }}
                </h3>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.activities.update', $activity->id) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                            <input type="text" name="title" id="title" class="form-control rounded-3" value="{{ old('title', $activity->title) }}" required>
                        </div>

                        <div class="col-12 col-md-4">
                            <label for="category" class="form-label fw-bold small text-dark">Categoría <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-select rounded-3" required>
                                <option value="Asamblea" {{ old('category', $activity->category) == 'Asamblea' ? 'selected' : '' }}>Asamblea General</option>
                                <option value="Minga" {{ old('category', $activity->category) == 'Minga' ? 'selected' : '' }}>Minga Comunitaria</option>
                                <option value="Taller" {{ old('category', $activity->category) == 'Taller' ? 'selected' : '' }}>Taller de Formación</option>
                                <option value="Cultura" {{ old('category', $activity->category) == 'Cultura' ? 'selected' : '' }}>Celebración Cultural Afro</option>
                                <option value="Reunión" {{ old('category', $activity->category) == 'Reunión' ? 'selected' : '' }}>Reunión de Junta</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="event_date" class="form-label fw-bold small text-dark">Fecha y Hora del Evento</label>
                            <input type="datetime-local" name="event_date" id="event_date" class="form-control rounded-3" value="{{ old('event_date', $activity->event_date ? $activity->event_date->format('Y-m-d\TH:i') : '') }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="location" class="form-label fw-bold small text-dark">Lugar / Sede / Vereda</label>
                            <input type="text" name="location" id="location" class="form-control rounded-3" value="{{ old('location', $activity->location) }}">
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label fw-bold small text-dark">Estado del Evento <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3" required>
                                <option value="programada" {{ old('status', $activity->status) == 'programada' ? 'selected' : '' }}>Programada (Próxima)</option>
                                <option value="en_curso" {{ old('status', $activity->status) == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                                <option value="finalizada" {{ old('status', $activity->status) == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                <option value="cancelada" {{ old('status', $activity->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-6 d-flex align-items-center mt-md-4 pt-md-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $activity->is_visible) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold text-dark" for="is_visible">
                                    Visible en el portal público
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label fw-bold small text-dark">Descripción y Objetivos <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="5" class="form-control rounded-3" required>{{ old('description', $activity->description) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <span class="text-muted small">
                            Registrado: {{ $activity->created_at->format('d/m/Y H:i') }}
                        </span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-admin-primary">
                                <i class="bi bi-save me-1"></i> Guardar Modificaciones
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
