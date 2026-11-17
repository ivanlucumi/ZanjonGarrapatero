@extends('layouts.admin')

@section('title', 'Buzón de Mensajes y Contacto Territorial')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-inbox-fill"></i> Atención a la Comunidad
            </span>
            <span class="text-muted small">&bull; Mensajes del Formulario de Contacto y Solicitudes</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Buzón de Mensajes & Trámites Ciudadanos</h2>
        <p class="text-muted small mb-0">
            Revisa, atiende y responde las inquietudes, solicitudes de censo y trámites de avales radicados a través del portal.
        </p>
    </div>
    @if($unreadCount > 0)
        <div>
            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-3 py-2 fs-6 rounded-pill">
                <i class="bi bi-bell-fill me-1"></i> {{ $unreadCount }} mensajes pendientes de atención
            </span>
        </div>
    @endif
</div>

<!-- FILTROS Y BÚSQUEDA -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.messages.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por nombre, correo, vereda o contenido..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Todos los estados</option>
                    <option value="pendiente" {{ $status === 'pendiente' ? 'selected' : '' }}>Pendientes de revisar</option>
                    <option value="en_tramite" {{ $status === 'en_tramite' ? 'selected' : '' }}>En trámite</option>
                    <option value="atendido" {{ $status === 'atendido' ? 'selected' : '' }}>Atendidos / Resueltos</option>
                    <option value="archivado" {{ $status === 'archivado' ? 'selected' : '' }}>Archivados</option>
                    <option value="trashed" {{ $status === 'trashed' ? 'selected' : '' }}>Papelera (SoftDelete)</option>
                </select>
            </div>
            <div class="col-6 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $status !== 'all')
                    <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE MENSAJES -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h3 class="admin-card-title mb-0">
            <i class="bi bi-envelope-paper-fill text-primary"></i>
            Mensajes Recibidos ({{ $messages->total() }})
        </h3>
        <span class="text-muted small">
            <i class="bi bi-shield-check text-success me-1"></i>Trazabilidad de respuestas y archivo SoftDelete
        </span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="min-width: 180px;">Remitente & Contacto</th>
                        <th>Vereda / Territorio</th>
                        <th style="min-width: 250px;">Mensaje / Solicitud</th>
                        <th>Estado</th>
                        <th>Fecha de Envío</th>
                        <th class="text-end" style="min-width: 150px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                        <tr class="{{ $msg->trashed() ? 'bg-light opacity-75' : ($msg->status === 'pendiente' ? 'table-warning-subtle' : '') }}">
                            <td>
                                <div class="fw-bold text-dark">{{ $msg->name }}</div>
                                <div class="small text-muted">{{ $msg->email }}</div>
                                @if($msg->phone)
                                    <div class="small text-secondary"><i class="bi bi-telephone me-1"></i>{{ $msg->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $msg->territory }}
                                </span>
                            </td>
                            <td>
                                <div class="small text-dark text-truncate" style="max-width: 320px;" title="{{ $msg->message }}">
                                    {{ $msg->message }}
                                </div>
                                @if($msg->admin_notes)
                                    <div class="small text-success mt-1">
                                        <i class="bi bi-chat-left-text me-1"></i>Nota interna: {{ Str::limit($msg->admin_notes, 60) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $st = $msg->statusBadge();
                                @endphp
                                @if($msg->trashed())
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 0.75rem;">
                                        <i class="bi bi-archive-fill me-1"></i>En Papelera
                                    </span>
                                @else
                                    <span class="badge border {{ $st['class'] }}" style="font-size: 0.72rem;">
                                        {{ $st['label'] }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-dark">{{ $msg->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $msg->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1 align-items-center">
                                    <!-- Ver / Atender -->
                                    <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn btn-sm btn-outline-primary" title="Ver detalle y responder">
                                        <i class="bi bi-eye"></i> Atender
                                    </a>

                                    @if(!$msg->trashed())
                                        <!-- Inactivar / Archivar -->
                                        <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" class="d-inline js-confirm-delete" data-confirm-title="¿Archivar mensaje?" data-confirm-text="El mensaje de '{{ $msg->name }}' pasará al archivo histórico mediante SoftDelete.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Archivar mensaje">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Restaurar -->
                                        <form action="{{ route('admin.messages.restore', $msg->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success" title="Restaurar al buzón">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                No se encontraron mensajes en el buzón con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
            <div class="px-3 py-3 border-top">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
