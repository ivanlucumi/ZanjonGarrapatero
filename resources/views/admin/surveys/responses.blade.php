@extends('layouts.admin')

@section('title', 'Respuestas de Censo — ' . $survey->title)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('admin.surveys.index') }}" class="btn btn-sm btn-admin-outline mb-2">
            <i class="bi bi-arrow-left"></i> Volver a Encuestas
        </a>
        <h2 class="h3 fw-bold text-dark mb-1">Auditoría de Respuestas: {{ $survey->title }}</h2>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-primary border">{{ $survey->category }}</span>
            <span class="badge bg-light text-dark border">{{ $survey->target_vereda ?? 'Todo el Territorio' }}</span>
            <span class="badge bg-success">{{ $responses->total() }} comuneros participantes</span>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">
            <i class="bi bi-person-check-fill text-warning"></i>
            Registros Censales Individuales ({{ $responses->total() }})
        </h3>
    </div>
    <div class="admin-card-body p-0">
        @if($responses->isEmpty())
            <div class="p-5 text-center text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                Aún no se han recibido respuestas para esta encuesta comunitaria.
            </div>
        @else
            <div class="table-responsive px-3 py-2">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Comunero / Informante</th>
                            <th>Vereda</th>
                            <th>Respuestas Capturadas</th>
                            <th>Observaciones</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($responses as $resp)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $resp->user->name ?? 'Usuario' }}</div>
                                    <div class="small text-muted">{{ $resp->user->email ?? '' }}</div>
                                    <div class="small text-secondary">Doc: {{ $resp->user->document_number ?? 'S/D' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $resp->user->vereda ?? 'Territorio' }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1 small">
                                        @if(is_array($resp->answers))
                                            @foreach($resp->answers as $key => $val)
                                                <div>
                                                    <span class="text-muted fw-semibold">{{ $key }}:</span>
                                                    <span class="text-dark">{{ is_array($val) ? implode(', ', $val) : $val }}</span>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Sin datos</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="small text-secondary">{{ $resp->notes ?? '—' }}</span>
                                </td>
                                <td>
                                    <span class="small text-secondary">{{ $resp->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3 border-top">
                {{ $responses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
