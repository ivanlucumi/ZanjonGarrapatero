@extends('layouts.admin')

@section('title', 'Detalle del Mensaje de Contacto')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <a href="{{ route('admin.messages.index') }}" class="btn btn-sm btn-admin-outline mb-2">
                    <i class="bi bi-arrow-left"></i> Volver al Buzón
                </a>
                <h2 class="h3 fw-bold text-dark mb-1">Solicitud / Mensaje de {{ $message->name }}</h2>
                <p class="text-muted small mb-0">Radicado el {{ $message->created_at->format('d/m/Y \a \l\a\s H:i') }} ({{ $message->created_at->diffForHumans() }})</p>
            </div>
            @if($message->trashed())
                <span class="badge bg-danger-subtle text-danger border border-danger p-2">
                    <i class="bi bi-archive-fill me-1"></i> Mensaje archivado en histórico (SoftDelete)
                </span>
            @endif
        </div>

        <!-- Tarjeta del Contenido del Mensaje -->
        <div class="admin-card mb-4">
            <div class="admin-card-header d-flex justify-content-between align-items-center">
                <h3 class="admin-card-title mb-0">
                    <i class="bi bi-envelope-open text-primary"></i>
                    Información del Remitente
                </h3>
                @php
                    $st = $message->statusBadge();
                @endphp
                <span class="badge border {{ $st['class'] }}">
                    {{ $st['label'] }}
                </span>
            </div>
            <div class="admin-card-body">
                <div class="row g-3 mb-4 pb-3 border-bottom">
                    <div class="col-12 col-md-4">
                        <span class="text-muted small d-block">Nombre Completo:</span>
                        <strong class="text-dark fs-6">{{ $message->name }}</strong>
                    </div>
                    <div class="col-12 col-md-4">
                        <span class="text-muted small d-block">Correo Electrónico:</span>
                        <a href="mailto:{{ $message->email }}" class="text-primary fw-semibold">{{ $message->email }}</a>
                    </div>
                    <div class="col-12 col-md-4">
                        <span class="text-muted small d-block">Teléfono / WhatsApp:</span>
                        @if($message->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $message->phone) }}" target="_blank" class="text-success fw-semibold">
                                <i class="bi bi-whatsapp me-1"></i>{{ $message->phone }}
                            </a>
                        @else
                            <span class="text-muted">No suministrado</span>
                        @endif
                    </div>
                    <div class="col-12 col-md-6">
                        <span class="text-muted small d-block">Vereda o Territorio:</span>
                        <span class="badge bg-light text-dark border fs-6">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $message->territory }}
                        </span>
                    </div>
                    <div class="col-12 col-md-6">
                        <span class="text-muted small d-block">Datos de Conexión:</span>
                        <span class="small text-muted font-monospace">IP: {{ $message->ip_address ?? '127.0.0.1' }}</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase" style="letter-spacing: 0.05em;">Contenido de la Inquietud o Solicitud:</label>
                    <div class="p-4 rounded-4 bg-light border text-dark" style="font-size: 0.95rem; line-height: 1.7; white-space: pre-wrap;">{{ $message->message }}</div>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Gestión Administrativa / Respuesta -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">
                    <i class="bi bi-pencil-square text-warning"></i>
                    Atención Administrativa & Notas Internas
                </h3>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.messages.update', $message->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label for="status" class="form-label fw-bold small text-dark">Estado de la Solicitud <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select rounded-3" required>
                                <option value="pendiente" {{ old('status', $message->status) == 'pendiente' ? 'selected' : '' }}>Pendiente (Sin revisar)</option>
                                <option value="en_tramite" {{ old('status', $message->status) == 'en_tramite' ? 'selected' : '' }}>En trámite (Asignado a comité/enlace)</option>
                                <option value="atendido" {{ old('status', $message->status) == 'atendido' ? 'selected' : '' }}>Atendido (Respuesta entregada al comunero)</option>
                                <option value="archivado" {{ old('status', $message->status) == 'archivado' ? 'selected' : '' }}>Archivado (Cerrado)</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="admin_notes" class="form-label fw-bold small text-dark">Bitácora de Seguimiento / Notas Internas</label>
                            <textarea name="admin_notes" id="admin_notes" rows="4" class="form-control rounded-3" placeholder="Registra las gestiones realizadas, llamadas, fecha de contacto, respuesta entregada...">{{ old('admin_notes', $message->admin_notes) }}</textarea>
                            <div class="form-text">Visible solo para los administradores del Consejo Comunitario.</div>
                        </div>
                    </div>

                    @if($message->attendedByUser)
                        <div class="alert alert-light border small text-muted mb-4 py-2">
                            <i class="bi bi-clock-history me-1"></i> Última atención registrada por <strong>{{ $message->attendedByUser->name }}</strong> el {{ $message->attended_at?->format('d/m/Y H:i') }}.
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                        <a href="mailto:{{ $message->email }}?subject=Respuesta a su solicitud - Consejo Comunitario Zanjón de Garrapatero" class="btn btn-outline-primary">
                            <i class="bi bi-reply-fill me-1"></i> Responder por Correo
                        </a>

                        <button type="submit" class="btn btn-admin-primary">
                            <i class="bi bi-save me-1"></i> Guardar Estado y Notas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
