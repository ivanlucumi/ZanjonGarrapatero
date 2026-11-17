@extends('layouts.admin')

@section('title', 'Diligenciar Encuesta — ' . $survey->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-8">
        <div class="mb-4">
            <a href="{{ route('admin.surveys.index') }}" class="btn btn-sm btn-admin-outline mb-2">
                <i class="bi bi-arrow-left"></i> Volver al Listado
            </a>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-light text-primary border">{{ $survey->category }}</span>
                <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt"></i> {{ $survey->target_vereda ?? 'Territorio Completo' }}</span>
            </div>
            <h2 class="h3 fw-bold text-dark mb-1">{{ $survey->title }}</h2>
            <p class="text-muted small mb-0">{{ $survey->description }}</p>
        </div>

        <div class="admin-card">
            <div class="admin-card-header bg-light-subtle">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle text-primary fs-5"></i>
                    <div>
                        <div class="fw-bold small text-dark">Comunero(a) Informante: {{ $user->name }}</div>
                        <div class="text-muted" style="font-size: 0.76rem;">Vereda: {{ $user->vereda ?? 'Zanjón de Garrapatero' }} &bull; Cédula: {{ $user->document_number ?? 'Comunero Censado' }}</div>
                    </div>
                </div>
            </div>

            <div class="admin-card-body p-4">
                <form action="{{ route('admin.surveys.submit', $survey) }}" method="POST">
                    @csrf
                    <input type="hidden" name="survey_id" value="{{ $survey->id }}">

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(is_array($survey->questions))
                        @foreach($survey->questions as $idx => $q)
                            @php
                                $qid = $q['id'] ?? 'q_' . $idx;
                                $label = $q['label'] ?? 'Pregunta ' . ($idx + 1);
                                $type = $q['type'] ?? 'text';
                            @endphp

                            <div class="p-3 mb-4 rounded-4 border bg-white shadow-sm" style="border-left: 4px solid var(--admin-gold) !important;">
                                <label class="form-label fw-bold text-dark d-block mb-2">
                                    <span class="badge bg-primary me-2">{{ $idx + 1 }}</span>
                                    {{ $label }}
                                    <span class="text-danger">*</span>
                                </label>

                                @if($type === 'textarea')
                                    <textarea name="answers[{{ $qid }}]" rows="3" class="form-control rounded-3" placeholder="Escriba su respuesta detallada..." required>{{ old("answers.$qid") }}</textarea>
                                @elseif($type === 'number')
                                    <input type="number" name="answers[{{ $qid }}]" class="form-control rounded-3" placeholder="0" min="0" value="{{ old("answers.$qid") }}" required>
                                @elseif($type === 'radio')
                                    <div class="d-flex flex-column gap-2 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[{{ $qid }}]" id="{{ $qid }}_si" value="Sí" required {{ old("answers.$qid") == 'Sí' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $qid }}_si">Sí</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[{{ $qid }}]" id="{{ $qid }}_no" value="No" {{ old("answers.$qid") == 'No' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $qid }}_no">No</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="answers[{{ $qid }}]" id="{{ $qid }}_parcial" value="Parcialmente / En proceso" {{ old("answers.$qid") == 'Parcialmente / En proceso' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="{{ $qid }}_parcial">Parcialmente / En proceso</label>
                                        </div>
                                    </div>
                                @elseif($type === 'select')
                                    <select name="answers[{{ $qid }}]" class="form-select rounded-3" required>
                                        <option value="">Seleccione una opción...</option>
                                        <option value="Agricultura tradicional" {{ old("answers.$qid") == 'Agricultura tradicional' ? 'selected' : '' }}>Agricultura tradicional</option>
                                        <option value="Piscicultura / Pesca" {{ old("answers.$qid") == 'Piscicultura / Pesca' ? 'selected' : '' }}>Piscicultura / Pesca</option>
                                        <option value="Ganadería menor" {{ old("answers.$qid") == 'Ganadería menor' ? 'selected' : '' }}>Ganadería menor</option>
                                        <option value="Artesanías y transformación" {{ old("answers.$qid") == 'Artesanías y transformación' ? 'selected' : '' }}>Artesanías y transformación</option>
                                        <option value="Comercio y servicios" {{ old("answers.$qid") == 'Comercio y servicios' ? 'selected' : '' }}>Comercio y servicios</option>
                                        <option value="Otro" {{ old("answers.$qid") == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                @else
                                    <input type="text" name="answers[{{ $qid }}]" class="form-control rounded-3" placeholder="Su respuesta..." value="{{ old("answers.$qid") }}" required>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <div class="mb-4">
                        <label for="notes" class="form-label fw-bold small text-dark">
                            <i class="bi bi-chat-left-dots text-warning me-1"></i>
                            Observaciones adicionales o sugerencias territoriales (Opcional):
                        </label>
                        <textarea name="notes" id="notes" rows="2" class="form-control rounded-3" placeholder="¿Desea agregar algún comentario para la Junta de Gobierno del Consejo?">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-3 border-top">
                        <a href="{{ route('admin.surveys.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Cancelar
                        </a>
                        <button type="submit" class="btn-admin-gold px-4 py-2">
                            <i class="bi bi-send-check-fill me-1"></i> Registrar y Enviar Respuestas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
