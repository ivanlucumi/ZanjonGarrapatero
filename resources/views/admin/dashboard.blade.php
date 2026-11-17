@extends('layouts.admin')

@section('title', 'Panel de Control — ' . $user->role->label())

@section('content')

{{-- ========================================================================= --}}
{{-- 1. VISTA SUPER ADMIN ("VE TODO")                                          --}}
{{-- ========================================================================= --}}
@if($user->isSuperAdmin())
    <div class="role-hero-banner">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge-role badge-role-superadmin">
                        <i class="bi bi-shield-shaded"></i> Super Administrador
                    </span>
                    <span class="text-white-50 small">&bull; Nivel Máximo de Seguridad & Gobernanza</span>
                </div>
                <h2 class="h3 fw-bold text-white mb-1">Bienvenido(a), {{ $user->name }}</h2>
                <p class="text-white-50 mb-0 small">
                    Tienes visibilidad global: métricas consolidadas del territorio, administración de usuarios, control de roles y gestión de encuestas censales.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light rounded-pill px-3 py-2 small fw-semibold">
                    <i class="bi bi-people-fill me-1"></i> Gestionar Usuarios
                </a>
                <a href="{{ route('admin.surveys.create') }}" class="btn-admin-gold">
                    <i class="bi bi-plus-circle-fill"></i> Crear Encuesta
                </a>
            </div>
        </div>
    </div>

    <!-- KPIs BENTO SUPER ADMIN -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="kpi-label">Comuneros Registrados</div>
                    <div class="kpi-value">{{ $totalUsers }}</div>
                    <div class="small text-muted" style="font-size: 0.74rem;">
                        {{ $usersByRole['super_admin'] }} Super &bull; {{ $usersByRole['admin'] }} Admin &bull; {{ $usersByRole['usuario'] }} Comuneros
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-gold">
                    <i class="bi bi-clipboard2-data-fill"></i>
                </div>
                <div>
                    <div class="kpi-label">Total de Encuestas</div>
                    <div class="kpi-value">{{ $totalSurveys }}</div>
                    <div class="small text-muted" style="font-size: 0.74rem;">
                        <span class="text-success fw-bold">{{ $activeSurveysCount }} activas</span> &bull; {{ $totalSurveys - $activeSurveysCount }} cerradas
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-success">
                    <i class="bi bi-chat-left-quote-fill"></i>
                </div>
                <div>
                    <div class="kpi-label">Respuestas de Censo</div>
                    <div class="kpi-value">{{ $totalResponses }}</div>
                    <div class="small text-muted" style="font-size: 0.74rem;">
                        Participación comunitaria directa
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-primary">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>
                <div>
                    <div class="kpi-label">Veredas Vinculadas</div>
                    <div class="kpi-value">{{ $activeVeredasCount }}</div>
                    <div class="small text-muted" style="font-size: 0.74rem;">
                        Sectores territoriales mapeados
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEPARADOR CON RELIEVE Y DOTA -->
    <div class="relief-divider">
        <div class="relief-divider-dot"></div>
    </div>

    <!-- CUADRO DE 2 COLUMNAS: ACTIVIDAD RECIENTE Y ACCESO RÁPIDO A USUARIOS -->
    <div class="row g-4">
        <!-- Columna Izquierda: Respuestas de Encuesta Recientes -->
        <div class="col-12 col-lg-7">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <i class="bi bi-clock-history text-warning"></i>
                        Últimas Respuestas de Censo Recibidas
                    </h3>
                    <a href="{{ route('admin.surveys.index') }}" class="btn btn-sm btn-admin-outline py-1 px-3">
                        Ver Encuestas
                    </a>
                </div>
                <div class="admin-card-body p-0">
                    @if($recentResponses->isEmpty())
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            Aún no se registran respuestas en las encuestas activas.
                        </div>
                    @else
                        <div class="table-responsive px-3 py-2">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Comunero</th>
                                        <th>Encuesta</th>
                                        <th>Vereda</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentResponses as $resp)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $resp->user->name ?? 'Anónimo' }}</div>
                                                <div class="small text-muted">{{ $resp->user->email ?? '' }}</div>
                                            </td>
                                            <td>
                                                <span class="fw-semibold text-primary">{{ Str::limit($resp->survey->title, 28) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $resp->user->vereda ?? 'Territorio' }}</span>
                                            </td>
                                            <td>
                                                <span class="small text-secondary">{{ $resp->created_at->diffForHumans() }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Columna Derecha: Resumen de Comuneros y Acceso de Roles -->
        <div class="col-12 col-lg-5">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <i class="bi bi-people-fill text-primary"></i>
                        Comuneros Registrados Recientemente
                    </h3>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-admin-outline py-1 px-3">
                        Administrar
                    </a>
                </div>
                <div class="admin-card-body">
                    <ul class="list-group list-group-flush mb-3">
                        @foreach($recentUsers as $ru)
                            <li class="list-group-item px-0 py-2 d-flex align-items-center justify-content-between border-bottom">
                                <div>
                                    <div class="fw-bold text-dark">{{ $ru->name }}</div>
                                    <div class="small text-muted">{{ $ru->email }} &bull; {{ $ru->vereda ?? 'Sin vereda' }}</div>
                                </div>
                                <div>
                                    <span class="badge-role {{ $ru->role->badgeClass() }}" style="font-size: 0.7rem; padding: 3px 10px;">
                                        {{ $ru->role->label() }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="text-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-admin-gold w-100 justify-content-center">
                            <i class="bi bi-shield-lock-fill"></i> Ir al Módulo de Asignación de Roles
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN CMS Y AUDITORÍA EN SUPER ADMIN -->
    <div class="row g-4 mt-1 mb-4">
        <!-- Bloque CMS: Publicaciones, Actividades y Multimedia -->
        <div class="col-12 col-lg-7">
            <div class="admin-card h-100">
                <div class="admin-card-header d-flex justify-content-between align-items-center">
                    <h3 class="admin-card-title mb-0">
                        <i class="bi bi-window-stack text-primary"></i>
                        Gestión Web & Contenidos (CMS)
                    </h3>
                    <span class="text-muted small">Estado del portal público</span>
                </div>
                <div class="admin-card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-4 text-center p-3 rounded-4 admin-glass-stat">
                            <i class="bi bi-newspaper fs-3 text-primary d-block mb-1"></i>
                            <div class="fw-bold fs-4 text-dark">{{ $totalPublications }}</div>
                            <small class="text-muted d-block">Noticias</small>
                            <a href="{{ route('admin.publications.index') }}" class="btn btn-sm btn-link p-0 mt-1">Gestionar</a>
                        </div>
                        <div class="col-4 text-center p-3 rounded-4 admin-glass-stat">
                            <i class="bi bi-calendar3 fs-3 text-warning d-block mb-1"></i>
                            <div class="fw-bold fs-4 text-dark">{{ $totalActivities }}</div>
                            <small class="text-muted d-block">Actividades</small>
                            <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-link p-0 mt-1">Gestionar</a>
                        </div>
                        <div class="col-4 text-center p-3 rounded-4 admin-glass-stat">
                            <i class="bi bi-collection-play fs-3 text-success d-block mb-1"></i>
                            <div class="fw-bold fs-4 text-dark">{{ $totalMedia }}</div>
                            <small class="text-muted d-block">Multimedia</small>
                            <a href="{{ route('admin.media.index') }}" class="btn btn-sm btn-link p-0 mt-1">Gestionar</a>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.publications.create') }}" class="btn btn-sm btn-admin-primary flex-grow-1 text-center">
                            <i class="bi bi-plus-circle me-1"></i> Redactar Noticia
                        </a>
                        <a href="{{ route('admin.activities.create') }}" class="btn btn-sm btn-admin-gold flex-grow-1 text-center">
                            <i class="bi bi-calendar-plus me-1"></i> Programar Evento
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bloque Auditoría y Sesiones -->
        <div class="col-12 col-lg-5">
            <div class="admin-card h-100">
                <div class="admin-card-header d-flex justify-content-between align-items-center">
                    <h3 class="admin-card-title mb-0">
                        <i class="bi bi-clock-history text-danger"></i>
                        Últimos Registros de Auditoría
                    </h3>
                    <a href="{{ route('admin.audit.index') }}" class="btn btn-sm btn-admin-outline py-1 px-3">
                        Ver Todo
                    </a>
                </div>
                <div class="admin-card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentAuditLogs as $al)
                            <div class="list-group-item px-3 py-2 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-secondary-subtle text-dark" style="font-size: 0.68rem;">{{ $al->action }}</span>
                                    <span class="text-muted" style="font-size: 0.7rem;">{{ $al->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="small text-dark fw-semibold text-truncate">{{ $al->description }}</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Por {{ $al->user_name }} (IP: {{ $al->ip_address }})</div>
                            </div>
                        @empty
                            <div class="p-3 text-muted small text-center">No hay registros de auditoría recientes.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>


{{-- ========================================================================= --}}
{{-- 2. VISTA ADMIN ("ENCUESTA")                                               --}}
{{-- ========================================================================= --}}
@elseif($user->isAdmin())
    <div class="role-hero-banner">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge-role badge-role-admin">
                        <i class="bi bi-clipboard2-data"></i> Administrador de Encuestas
                    </span>
                    <span class="text-white-50 small">&bull; Levantamiento Territorial y Censo</span>
                </div>
                <h2 class="h3 fw-bold text-white mb-1">Panel de Encuestas & Censo Comunitario</h2>
                <p class="text-white-50 mb-0 small">
                    Tienes permisos exclusivos para diseñar encuestas, abrir o cerrar convocatorias censales y auditar las respuestas del territorio.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.surveys.create') }}" class="btn-admin-gold">
                    <i class="bi bi-plus-circle-fill"></i> Nueva Encuesta
                </a>
            </div>
        </div>
    </div>

    <!-- KPIs ADMIN ENCUESTA -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-4">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-primary">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <div>
                    <div class="kpi-label">Total de Encuestas</div>
                    <div class="kpi-value">{{ $totalSurveys }}</div>
                    <div class="small text-muted" style="font-size: 0.74rem;">Formularios gestionados</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-4">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-gold">
                    <i class="bi bi-activity"></i>
                </div>
                <div>
                    <div class="kpi-label">Encuestas Activas</div>
                    <div class="kpi-value">{{ $activeSurveysCount }}</div>
                    <div class="small text-muted" style="font-size: 0.74rem;">Abiertas para comuneros</div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-4">
            <div class="kpi-card">
                <div class="kpi-icon kpi-icon-success">
                    <i class="bi bi-check2-all"></i>
                </div>
                <div>
                    <div class="kpi-label">Respuestas Totales</div>
                    <div class="kpi-value">{{ $totalResponses }}</div>
                    <div class="small text-muted" style="font-size: 0.74rem;">Registros censales capturados</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEPARADOR -->
    <div class="relief-divider">
        <div class="relief-divider-dot"></div>
    </div>

    <!-- LISTADO DE ENCUESTAS GESTIONADAS -->
    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="bi bi-list-check text-primary"></i>
                Encuestas Comunitarias y Censos en Marcha
            </h3>
            <a href="{{ route('admin.surveys.create') }}" class="btn btn-sm btn-admin-gold py-1 px-3">
                <i class="bi bi-plus-lg"></i> Crear Encuesta
            </a>
        </div>
        <div class="admin-card-body p-0">
            @if($activeSurveys->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-clipboard-x fs-1 d-block mb-2 text-secondary"></i>
                    No hay encuestas creadas en este momento. ¡Comienza creando la primera!
                </div>
            @else
                <div class="table-responsive px-3 py-2">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Título de la Encuesta</th>
                                <th>Categoría</th>
                                <th>Vereda Destino</th>
                                <th>Estado</th>
                                <th>Respuestas</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeSurveys as $survey)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $survey->title }}</div>
                                        <div class="small text-muted">{{ Str::limit($survey->description, 50) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border">{{ $survey->category }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $survey->target_vereda ?? 'Todas las veredas' }}</span>
                                    </td>
                                    <td>
                                        @if($survey->status === 'active')
                                            <span class="badge bg-success">Activa</span>
                                        @else
                                            <span class="badge bg-secondary">Cerrada</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-6 text-primary">{{ $survey->responses_count }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.surveys.responses', $survey) }}" class="btn btn-outline-primary" title="Ver Respuestas Recibidas">
                                                <i class="bi bi-eye"></i> Respuestas
                                            </a>
                                            <form action="{{ route('admin.surveys.toggle-status', $survey) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn {{ $survey->status === 'active' ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $survey->status === 'active' ? 'Cerrar Encuesta' : 'Reabrir Encuesta' }}">
                                                    {{ $survey->status === 'active' ? 'Cerrar' : 'Reabrir' }}
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- ACCESO RÁPIDO CMS PARA ADMIN -->
    <div class="admin-card mb-4">
        <div class="admin-card-header d-flex justify-content-between align-items-center">
            <h3 class="admin-card-title mb-0">
                <i class="bi bi-window-stack text-primary"></i>
                Gestión de Publicaciones y Actividades del Portal
            </h3>
            <span class="text-muted small">Contenidos Públicos</span>
        </div>
        <div class="admin-card-body">
            <div class="row g-3">
                <div class="col-12 col-md-4 text-center p-3 rounded-4 admin-glass-stat">
                    <i class="bi bi-newspaper fs-3 text-primary d-block mb-1"></i>
                    <div class="fw-bold fs-4 text-dark">{{ $totalPublications }}</div>
                    <small class="text-muted d-block">Noticias Registradas</small>
                    <a href="{{ route('admin.publications.index') }}" class="btn btn-sm btn-link p-0 mt-1">Administrar Noticias</a>
                </div>
                <div class="col-12 col-md-4 text-center p-3 rounded-4 admin-glass-stat">
                    <i class="bi bi-calendar3 fs-3 text-warning d-block mb-1"></i>
                    <div class="fw-bold fs-4 text-dark">{{ $totalActivities }}</div>
                    <small class="text-muted d-block">Actividades en Agenda</small>
                    <a href="{{ route('admin.activities.index') }}" class="btn btn-sm btn-link p-0 mt-1">Administrar Agenda</a>
                </div>
                <div class="col-12 col-md-4 text-center p-3 rounded-4 admin-glass-stat">
                    <i class="bi bi-collection-play fs-3 text-success d-block mb-1"></i>
                    <div class="fw-bold fs-4 text-dark">{{ $totalMedia }}</div>
                    <small class="text-muted d-block">Fotos y Videos</small>
                    <a href="{{ route('admin.media.index') }}" class="btn btn-sm btn-link p-0 mt-1">Administrar Multimedia</a>
                </div>
            </div>
        </div>
    </div>


{{-- ========================================================================= --}}
{{-- 3. VISTA USUARIO / COMUNERO ("USUARIO")                                    --}}
{{-- ========================================================================= --}}
@else
    <div class="role-hero-banner">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge-role badge-role-usuario">
                        <i class="bi bi-person-check"></i> Comunero / Miembro del Territorio
                    </span>
                    <span class="text-white-50 small">&bull; Consejo Comunitario Zanjón de Garrapatero</span>
                </div>
                <h2 class="h3 fw-bold text-white mb-1">¡Bienvenido(a), {{ $user->name }}!</h2>
                <p class="text-white-50 mb-0 small">
                    Tu participación fortalece nuestra autonomía comunitaria. Aquí puedes diligenciar las encuestas de censo territorial y consultar tus respuestas registradas.
                </p>
            </div>
            <div>
                <a href="{{ route('admin.surveys.index') }}" class="btn-admin-gold">
                    <i class="bi bi-pencil-square"></i> Ver Encuestas Disponibles
                </a>
            </div>
        </div>
    </div>

    <!-- FICHA TERRITORIAL DEL COMUNERO & ESTADÍSTICAS -->
    <div class="row g-4 mb-4">
        <!-- Ficha Personal -->
        <div class="col-12 col-md-5">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">
                        <i class="bi bi-card-heading text-primary"></i>
                        Ficha Territorial del Comunero
                    </h3>
                </div>
                <div class="admin-card-body">
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                        <div class="kpi-icon kpi-icon-primary">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-5 text-dark">{{ $user->name }}</div>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="row g-2 small">
                        <div class="col-6">
                            <span class="text-muted d-block">Vereda de Residencia:</span>
                            <span class="fw-bold text-dark">{{ $user->vereda ?? 'Zanjón de Garrapatero' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-muted d-block">Documento de Identidad:</span>
                            <span class="fw-bold text-dark">{{ $user->document_number ?? 'Por registrar' }}</span>
                        </div>
                        <div class="col-6 mt-2">
                            <span class="text-muted d-block">Teléfono / Contacto:</span>
                            <span class="fw-bold text-dark">{{ $user->phone ?? 'Sin registrar' }}</span>
                        </div>
                        <div class="col-6 mt-2">
                            <span class="text-muted d-block">Estado Comunitario:</span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Censado Activo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPIs Comunero -->
        <div class="col-12 col-md-7">
            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <div class="kpi-card h-100">
                        <div class="kpi-icon kpi-icon-gold">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Encuestas Pendientes</div>
                            <div class="kpi-value text-warning">{{ $pendingCount }}</div>
                            <div class="small text-muted">Disponibles para tu vereda</div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="kpi-card h-100">
                        <div class="kpi-icon kpi-icon-success">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <div class="kpi-label">Encuestas Diligenciadas</div>
                            <div class="kpi-value text-success">{{ $answeredCount }}</div>
                            <div class="small text-muted">Aportes a la comunidad</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aviso Territorial -->
            <div class="alert alert-light border shadow-sm rounded-4 mt-3 mb-0 p-3">
                <div class="d-flex align-items-start gap-2">
                    <i class="bi bi-info-circle-fill text-warning fs-5"></i>
                    <div class="small text-secondary">
                        <strong class="text-dark">Importancia del Censo Territorial:</strong> La información capturada en estas encuestas es confidencial y utilizada exclusivamente por la Junta de Gobierno del Consejo Comunitario para la formulación de proyectos productivos, planes de etnodesarrollo y defensa del territorio ancestral.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEPARADOR -->
    <div class="relief-divider">
        <div class="relief-divider-dot"></div>
    </div>

    <!-- ENCUESTAS PENDIENTES POR RESPONDER -->
    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="bi bi-pencil-square text-warning"></i>
                Encuestas Comunitarias Disponibles para Diligenciar
            </h3>
        </div>
        <div class="admin-card-body">
            @if($pendingSurveys->isEmpty())
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-check2-circle fs-1 text-success d-block mb-2"></i>
                    <h5 class="fw-bold text-dark">¡Estás al día!</h5>
                    <p class="small text-muted mb-0">Has respondido todas las encuestas y censos activos correspondientes a tu vereda.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($pendingSurveys as $survey)
                        <div class="col-12 col-lg-6">
                            <div class="p-3 rounded-4 border bg-white shadow-sm h-100 d-flex flex-column justify-content-between" style="border-left: 4px solid var(--admin-gold) !important;">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-light text-primary border">{{ $survey->category }}</span>
                                        <span class="badge bg-warning-subtle text-warning-emphasis border">Pendiente</span>
                                    </div>
                                    <h4 class="h5 fw-bold text-dark mb-1">{{ $survey->title }}</h4>
                                    <p class="text-muted small mb-3">{{ Str::limit($survey->description, 110) }}</p>
                                </div>
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                    <span class="small text-secondary">
                                        <i class="bi bi-geo-alt"></i> {{ $survey->target_vereda ?? 'Todo el Territorio' }}
                                    </span>
                                    <a href="{{ route('admin.surveys.respond', $survey) }}" class="btn-admin-gold py-1 px-3 small">
                                        <i class="bi bi-pencil-fill me-1"></i> Diligenciar Ahora
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- HISTORIAL DE ENCUESTAS DILIGENCIADAS -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">
                <i class="bi bi-journal-check text-success"></i>
                Mis Encuestas Registradas
            </h3>
        </div>
        <div class="admin-card-body p-0">
            @if($myResponses->isEmpty())
                <div class="p-4 text-center text-muted small">
                    Aún no has enviado respuestas. Al completar una encuesta, aparecerá en esta sección como constancia de participación.
                </div>
            @else
                <div class="table-responsive px-3 py-2">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Encuesta</th>
                                <th>Categoría</th>
                                <th>Fecha de Envío</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myResponses as $resp)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $resp->survey->title }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-primary border">{{ $resp->survey->category }}</span>
                                    </td>
                                    <td>
                                        <span class="small text-secondary">{{ $resp->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i> Registrada en Censo
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endif

@endsection
