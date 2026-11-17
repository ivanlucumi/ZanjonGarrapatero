@extends('layouts.admin')

@section('title', 'Detalle de Encuesta — ' . $survey->title)

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <a href="{{ route('admin.surveys.index') }}" class="btn btn-sm btn-admin-outline mb-2">
                    <i class="bi bi-arrow-left"></i> Volver a Encuestas
                </a>
                <h2 class="h3 fw-bold text-dark mb-1">{{ $survey->title }}</h2>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-primary border">{{ $survey->category }}</span>
                    <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt"></i> {{ $survey->target_vereda ?? 'Todo el Territorio' }}</span>
                    @if($survey->status === 'active')
                        <span class="badge bg-success">Activa</span>
                    @else
                        <span class="badge bg-secondary">Cerrada</span>
                    @endif
                </div>
            </div>
            @if(Auth::user()->role->canManageSurveys())
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.surveys.responses', $survey) }}" class="btn-admin-gold">
                        <i class="bi bi-bar-chart-fill"></i> Ver {{ $survey->responses_count }} Respuestas
                    </a>
                </div>
            @endif
        </div>

        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="bi bi-card-text text-warning"></i>
                    Objetivo y Contexto Comunitario
                </h3>
            </div>
            <div class="admin-card-body">
                <p class="text-secondary leading-relaxed mb-0">
                    {{ $survey->description ?? 'Esta encuesta recopila insumos territoriales para la asamblea general de comuneros y la Junta de Gobierno del Consejo Comunitario.' }}
                </p>
            </div>
        </div>

        <!-- LISTA DE PREGUNTAS -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="bi bi-question-circle-fill text-primary"></i>
                    Estructura del Cuestionario ({{ is_array($survey->questions) ? count($survey->questions) : 0 }} preguntas)
                </h3>
            </div>
            <div class="admin-card-body">
                @if(is_array($survey->questions))
                    <ol class="list-group list-group-numbered list-group-flush">
                        @foreach($survey->questions as $q)
                            <li class="list-group-item py-3 px-2 d-flex justify-content-between align-items-start border-bottom">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold text-dark">{{ $q['label'] ?? '' }}</div>
                                    <span class="small text-muted">Tipo: <span class="badge bg-light text-secondary border">{{ $q['type'] ?? 'text' }}</span></span>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="text-muted mb-0">No se definieron preguntas estructuradas.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
