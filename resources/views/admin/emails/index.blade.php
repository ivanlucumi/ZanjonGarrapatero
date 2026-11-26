@extends('layouts.admin')

@section('title', 'Centro de Correos Institucionales')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <span class="text-uppercase fw-bold small" style="color: #8d6e63; letter-spacing: 0.1em;">Comunicaciones · Envíos</span>
        <h2 class="h3 fw-bold mb-1" style="color: #3e2723;">Centro de Correos</h2>
        <p class="text-muted small mb-0">Envía comunicados individuales, segmentados por rol o masivos a todos los comuneros inscritos.</p>
    </div>
    <a href="{{ route('admin.emails.create') }}" class="btn btn-admin-gold">
        <i class="bi bi-send-plus-fill"></i> Redactar Correo
    </a>
</div>

<div class="admin-card p-3 p-md-4 mb-4">
    <form method="GET" action="{{ route('admin.emails.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
        <span class="small fw-bold text-muted me-2">Filtrar por tipo:</span>
        <a href="{{ route('admin.emails.index') }}" class="btn btn-sm {{ $sendType === 'all' ? 'btn-admin-primary' : 'btn-admin-outline' }}">Todos</a>
        <a href="{{ route('admin.emails.index', ['send_type' => 'individual']) }}" class="btn btn-sm {{ $sendType === 'individual' ? 'btn-admin-primary' : 'btn-admin-outline' }}">Individuales</a>
        <a href="{{ route('admin.emails.index', ['send_type' => 'role']) }}" class="btn btn-sm {{ $sendType === 'role' ? 'btn-admin-primary' : 'btn-admin-outline' }}">Por Rol</a>
        <a href="{{ route('admin.emails.index', ['send_type' => 'massive']) }}" class="btn btn-sm {{ $sendType === 'massive' ? 'btn-admin-primary' : 'btn-admin-outline' }}">Masivos</a>
    </form>
</div>

<div class="admin-card p-3 p-md-4">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Asunto</th>
                    <th>Destinatarios</th>
                    <th>Remitente</th>
                    <th>Fecha de Envío</th>
                    <th class="text-end">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($emails as $email)
                    <tr>
                        <td>
                            @if($email->send_type === 'massive')
                                <span class="badge bg-warning text-dark"><i class="bi bi-broadcast me-1"></i> Masivo</span>
                            @elseif($email->send_type === 'role')
                                <span class="badge bg-info text-dark"><i class="bi bi-people-fill me-1"></i> Rol: {{ $email->target_role }}</span>
                            @else
                                <span class="badge bg-secondary"><i class="bi bi-person-fill me-1"></i> Individual</span>
                            @endif
                        </td>
                        <td class="fw-semibold text-dark">{{ $email->subject }}</td>
                        <td>
                            <span class="fw-bold" style="color: #6d4c3d;">{{ $email->recipients_count }}</span>
                            <span class="small text-muted">inscrito(s)</span>
                        </td>
                        <td class="small text-muted">{{ $email->sender?->name ?? 'Sistema' }}</td>
                        <td class="small text-muted">{{ $email->sent_at?->format('d/m/Y H:i') ?? $email->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.emails.show', $email->id) }}" class="btn btn-sm btn-admin-outline">
                                <i class="bi bi-eye"></i> Ver detalle
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-envelope-open fs-2 d-block mb-2"></i>
                            Aún no se han enviado correos desde el panel administrativo.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $emails->links() }}
    </div>
</div>
@endsection
