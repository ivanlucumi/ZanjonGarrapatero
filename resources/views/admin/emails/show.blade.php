@extends('layouts.admin')

@section('title', 'Detalle de Correo Enviado')

@section('content')
<div class="container-fluid px-0" style="max-width: 880px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-uppercase fw-bold small" style="color: #8d6e63; letter-spacing: 0.1em;">Registro de Envío #{{ $sentEmail->id }}</span>
            <h2 class="h3 fw-bold mb-0" style="color: #3e2723;">{{ $sentEmail->subject }}</h2>
        </div>
        <a href="{{ route('admin.emails.index') }}" class="btn btn-admin-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="admin-card p-4 mb-4">
        <div class="row g-3 mb-4 pb-3 border-bottom">
            <div class="col-md-3">
                <span class="small text-muted d-block">Tipo de Envío</span>
                <strong class="text-dark">{{ $sentEmail->typeLabel() }}</strong>
            </div>
            <div class="col-md-3">
                <span class="small text-muted d-block">Remitente</span>
                <strong class="text-dark">{{ $sentEmail->sender?->name ?? 'Sistema' }}</strong>
            </div>
            <div class="col-md-3">
                <span class="small text-muted d-block">Fecha de Envío</span>
                <strong class="text-dark">{{ $sentEmail->sent_at?->format('d/m/Y H:i') ?? $sentEmail->created_at->format('d/m/Y H:i') }}</strong>
            </div>
            <div class="col-md-3">
                <span class="small text-muted d-block">Estado de Entrega</span>
                <strong class="text-success">{{ $sentEmail->recipients_count }} enviados</strong>
                @if($sentEmail->failed_count > 0)
                    <span class="text-danger small d-block">{{ $sentEmail->failed_count }} fallaron tras reintentos</span>
                @endif
            </div>
        </div>

        <h6 class="fw-bold small text-uppercase text-muted mb-2">Cuerpo del Mensaje</h6>
        <div class="p-3 rounded-3 border bg-light mb-4" style="white-space: pre-line; line-height: 1.7;">{{ $sentEmail->body }}</div>

        @if(! empty($sentEmail->attachments_meta))
            <h6 class="fw-bold small text-uppercase text-muted mb-2">Archivos Adjuntos / Enlaces ({{ count($sentEmail->attachments_meta) }})</h6>
            <div class="d-flex flex-wrap gap-2 mb-4">
                @foreach($sentEmail->attachments_meta as $att)
                    <a href="{{ $att['url'] }}" target="_blank" class="btn btn-sm btn-admin-outline">
                        <i class="bi {{ ($att['delivery_mode'] ?? '') === 'link' ? 'bi-link-45deg' : 'bi-paperclip' }} me-1"></i>
                        {{ $att['name'] }} ({{ $att['size_label'] }})
                        <span class="badge bg-light text-dark border ms-1">{{ ($att['delivery_mode'] ?? '') === 'link' ? 'Enlace descarga' : 'Adjunto directo' }}</span>
                    </a>
                @endforeach
            </div>
        @endif

        <h6 class="fw-bold small text-uppercase text-muted mb-2">Destinatarios ({{ $sentEmail->recipients_count }})</h6>
        <div class="d-flex flex-wrap gap-2">
            @foreach($sentEmail->recipient_emails ?? [] as $emailAddress)
                <span class="badge bg-white text-dark border px-3 py-2">{{ $emailAddress }}</span>
            @endforeach
        </div>
    </div>
</div>
@endsection
