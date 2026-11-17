@extends('layouts.admin')

@section('title', 'Encuestas y Censos Comunitarios')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">Encuestas y Censos Territoriales</h2>
        <p class="text-muted small mb-0">
            Instrumentos de consulta y recolección de información comunitaria para la toma de decisiones autónomas.
        </p>
    </div>

    @if(Auth::user()->role->canManageSurveys())
        <div>
            <a href="{{ route('admin.surveys.create') }}" class="btn-admin-gold">
                <i class="bi bi-plus-circle-fill"></i> Crear Nueva Encuesta
            </a>
        </div>
    @endif
</div>

<!-- SEPARADOR -->
<div class="relief-divider">
    <div class="relief-divider-dot"></div>
</div>

@if(Auth::user()->isUsuario())
    {{-- Vista para comunero --}}
    <div class="row g-4">
        @forelse($surveys as $survey)
            @php
                $hasAnswered = in_array($survey->id, $myResponseSurveyIds, true);
            @endphp
            <div class="col-12 col-md-6 col-lg-4">
                <div class="admin-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="admin-card-header">
                            <span class="badge bg-light text-primary border">{{ $survey->category }}</span>
                            @if($hasAnswered)
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="bi bi-check-all"></i> Diligenciada
                                </span>
                            @elseif($survey->isActive())
                                <span class="badge bg-warning-subtle text-warning-emphasis border">
                                    <i class="bi bi-hourglass-split"></i> Pendiente
                                </span>
                            @else
                                <span class="badge bg-secondary">Cerrada</span>
                            @endif
                        </div>
                        <div class="admin-card-body">
                            <h4 class="h5 fw-bold text-dark mb-2">{{ $survey->title }}</h4>
                            <p class="text-muted small mb-3">{{ Str::limit($survey->description, 130) }}</p>
                            
                            <div class="small text-secondary mb-2">
                                <i class="bi bi-geo-alt-fill text-warning me-1"></i>
                                Vereda: <strong>{{ $survey->target_vereda ?? 'Todo el Territorio' }}</strong>
                            </div>
                            <div class="small text-secondary">
                                <i class="bi bi-question-circle-fill text-primary me-1"></i>
                                Preguntas formuladas: <strong>{{ is_array($survey->questions) ? count($survey->questions) : 0 }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 border-top bg-light-subtle text-end">
                        @if($hasAnswered)
                            <button class="btn btn-sm btn-outline-success rounded-pill px-3" disabled>
                                <i class="bi bi-check-circle-fill me-1"></i> Respuesta Registrada
                            </button>
                        @elseif($survey->isActive())
                            <a href="{{ route('admin.surveys.respond', $survey) }}" class="btn-admin-gold py-1 px-3 small">
                                <i class="bi bi-pencil-fill me-1"></i> Diligenciar Censo
                            </a>
                        @else
                            <button class="btn btn-sm btn-secondary rounded-pill px-3" disabled>
                                Convocatoria Cerrada
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="admin-card p-5 text-center text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-3 text-secondary"></i>
                    No hay encuestas asignadas o activas para tu territorio en este momento.
                </div>
            </div>
        @endforelse
    </div>
@else
    {{-- Vista para SuperAdmin y Admin --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="bi bi-clipboard2-data-fill text-warning"></i>
                Listado Consolidado de Encuestas Comunitarias
            </h3>
        </div>
        <div class="admin-card-body p-0">
            @if($surveys->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-clipboard-x fs-1 d-block mb-3 text-secondary"></i>
                    Aún no se han configurado encuestas. Haz clic en "Crear Nueva Encuesta" para comenzar.
                </div>
            @else
                <div class="table-responsive px-3 py-2">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Encuesta</th>
                                <th>Categoría</th>
                                <th>Alcance Territorial</th>
                                <th>Respuestas</th>
                                <th>Estado</th>
                                <th>Creador</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($surveys as $survey)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $survey->title }}</div>
                                        <div class="small text-muted">{{ Str::limit($survey->description, 60) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border">{{ $survey->category }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $survey->target_vereda ?? 'General (Todas)' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.surveys.responses', $survey) }}" class="fw-bold text-primary text-decoration-none">
                                            <i class="bi bi-chat-left-dots-fill me-1"></i> {{ $survey->responses_count }} respuestas
                                        </a>
                                    </td>
                                    <td>
                                        @if($survey->status === 'active')
                                            <span class="badge bg-success">Activa</span>
                                        @else
                                            <span class="badge bg-secondary">Cerrada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="small text-secondary">{{ $survey->creator->name ?? 'Sistema' }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.surveys.show', $survey) }}" class="btn btn-outline-secondary" title="Detalle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.surveys.responses', $survey) }}" class="btn btn-outline-primary" title="Respuestas">
                                                <i class="bi bi-bar-chart-fill"></i>
                                            </a>
                                            <form action="{{ route('admin.surveys.toggle-status', $survey) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn {{ $survey->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $survey->status === 'active' ? 'Cerrar Encuesta' : 'Reabrir Encuesta' }}">
                                                    {{ $survey->status === 'active' ? 'Cerrar' : 'Activar' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">
                    {{ $surveys->links() }}
                </div>
            @endif
        </div>
    </div>
@endif

@endsection
