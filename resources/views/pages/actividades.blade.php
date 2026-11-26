@extends('layouts.index')

@section('title', 'Actividades | Consejo Comunitario Zanjón de Garrapatero')
@section('meta_description', 'Programas, asambleas comunitarias, recorridos de la Guardia Cimarrona y eventos culturales del Consejo Comunitario Zanjón de Garrapatero.')

@section('content')
<div class="page-header">
    <div class="wrap">
        <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            <span>/</span>
            <span>Actividades</span>
        </nav>
        <span class="glass-pill glass-pill-gold mb-2">03 · Organización y encuentros</span>
        <h1 class="page-header-title serif">La comunidad se fortalece en el encuentro.</h1>
        <p class="page-header-subtitle">
            Cada asamblea, minga, recorrido de guardia o encuentro cultural es un paso más en la consolidación de nuestra autonomía territorial.
        </p>
    </div>
</div>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

<section class="section">
    <div class="wrap">
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <article class="activity-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <i class="bi bi-megaphone fs-2" style="color: var(--brand-accent);"></i>
                        <span class="glass-pill glass-pill-gold">Permanente</span>
                    </div>
                    <h3>Asambleas Comunitarias</h3>
                    <p>Espacios soberanos de deliberación donde se toman las decisiones sobre territorio, proyectos y administración comunitaria.</p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="activity-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <i class="bi bi-shield-shaded fs-2" style="color: var(--brand-primary);"></i>
                        <span class="glass-pill glass-pill-blue">Custodia</span>
                    </div>
                    <h3>Guardia Cimarrona</h3>
                    <p>Recorridos de prevención territorial, resolución pacífica de controversias y acompañamiento a las comitivas comunitarias.</p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="activity-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <i class="bi bi-music-note-beamed fs-2" style="color: var(--brand-accent);"></i>
                        <span class="glass-pill glass-pill-gold">Tradición</span>
                    </div>
                    <h3>Cultura y Saberes</h3>
                    <p>Encuentros de percusión, cantos tradicionales, gastronomía típica afro y conservación de la medicina de nuestros mayores.</p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="activity-card">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <i class="bi bi-clipboard2-check fs-2" style="color: var(--brand-primary);"></i>
                        <span class="glass-pill glass-pill-blue">Gestión</span>
                    </div>
                    <h3>Censo y Avales</h3>
                    <p>Actualización sistemática del padrón comunitario, emisión de certificaciones de pertenencia étnica y avales institucionales.</p>
                </article>
            </div>
        </div>

        {{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
        <div class="relief-divider" aria-hidden="true"></div>

        {{-- Calendario Interactivo de Eventos (FullCalendar) --}}
        <div class="section-header">
            <span class="kicker">Calendario territorial</span>
            <h2 class="section-title serif">Calendario de Eventos y Convocatorias {{ date('Y') }}</h2>
            <p class="section-copy">
                Explora las fechas programadas en el calendario interactivo o consulta el listado inferior. Haz clic en cualquier evento para ver los detalles.
            </p>
        </div>

        @php
            $calendarEvents = collect($activities ?? [])->map(function ($act) {
                return [
                    'id' => $act->id,
                    'title' => $act->title,
                    'start' => $act->event_date ? $act->event_date->toIso8601String() : now()->toIso8601String(),
                    'color' => match($act->category) {
                        'Minga' => '#6d4c3d',
                        'Cultura' => '#d97706',
                        'Taller' => '#0d9488',
                        default => '#2d51ad',
                    },
                    'extendedProps' => [
                        'category' => $act->category,
                        'location' => $act->location ?? 'Zanjón de Garrapatero',
                        'status' => ucfirst(str_replace('_', ' ', $act->status ?? 'programada')),
                        'description' => $act->description,
                        'formatted_date' => $act->event_date ? $act->event_date->format('d/m/Y h:i A') : 'Por definir',
                    ],
                ];
            })->values();

            if ($calendarEvents->isEmpty()) {
                $calendarEvents = collect([
                    [
                        'id' => 'demo-1',
                        'title' => 'Asamblea General y Socialización del Censo',
                        'start' => now()->addDays(3)->setTime(9, 0)->toIso8601String(),
                        'color' => '#2d51ad',
                        'extendedProps' => [
                            'category' => 'Asamblea',
                            'location' => 'Salón Comunitario El Palmar',
                            'status' => 'Programada',
                            'description' => 'Reunión formal con líderes veredales y comunidad general para concertar la ruta digital.',
                            'formatted_date' => now()->addDays(3)->setTime(9, 0)->format('d/m/Y h:i A'),
                        ],
                    ],
                    [
                        'id' => 'demo-2',
                        'title' => 'Recorrido Ambiental en Cuenca Hídrica',
                        'start' => now()->addDays(8)->setTime(7, 30)->toIso8601String(),
                        'color' => '#6d4c3d',
                        'extendedProps' => [
                            'category' => 'Minga',
                            'location' => 'Bajo San Francisco y Santa Lucía',
                            'status' => 'Programada',
                            'description' => 'Jornada de siembra comunitaria de especies nativas y verificación de linderos ancestrales.',
                            'formatted_date' => now()->addDays(8)->setTime(7, 30)->format('d/m/Y h:i A'),
                        ],
                    ],
                ]);
            }
        @endphp

        <div class="glass-card p-3 p-md-4 mb-5">
            <div id="communityFullCalendar"></div>
        </div>

        {{-- Modal de Detalle de Evento de FullCalendar --}}
        <div class="modal fade" id="fcEventModal" tabindex="-1" aria-labelledby="fcEventTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header border-0 text-white" style="background: linear-gradient(135deg, #2d51ad 0%, #1e3a8a 100%);">
                        <div>
                            <span id="fcEventCategory" class="badge bg-warning text-dark mb-1">Evento</span>
                            <h3 class="modal-title fs-5 fw-bold mb-0" id="fcEventTitle">Detalle del Evento</h3>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body p-4 bg-white">
                        <div class="d-flex flex-column gap-2 mb-3 small text-secondary">
                            <div><i class="bi bi-calendar-event me-2 text-primary"></i><strong>Fecha y Hora:</strong> <span id="fcEventDate"></span></div>
                            <div><i class="bi bi-geo-alt-fill me-2 text-danger"></i><strong>Lugar / Vereda:</strong> <span id="fcEventLocation"></span></div>
                            <div><i class="bi bi-flag-fill me-2 text-success"></i><strong>Estado:</strong> <span id="fcEventStatus"></span></div>
                        </div>
                        <p id="fcEventDescription" class="mb-0 text-dark" style="line-height: 1.65;"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @if(isset($activities) && $activities->isNotEmpty())
                @foreach($activities as $act)
                    <div class="col-lg-6">
                        <div class="glass-card p-4 d-flex gap-4 align-items-start">
                            <div class="p-3 text-center rounded-4 flex-shrink-0" style="background: rgba(181, 138, 69, 0.15); border: 1px solid var(--brand-accent); min-width: 80px;">
                                <span class="d-block text-uppercase small fw-bold" style="color: var(--brand-accent);">
                                    {{ $act->event_date ? $act->event_date->translatedFormat('M') : 'PRÓX' }}
                                </span>
                                <strong class="d-block fs-3" style="color: var(--brand-primary);">
                                    {{ $act->event_date ? $act->event_date->format('d') : '—' }}
                                </strong>
                            </div>
                            <div>
                                <span class="badge mb-2" style="background: var(--brand-primary); color: #ffffff;">{{ $act->category }}</span>
                                <h3 class="h5 mb-2" style="color: var(--brand-primary);">{{ $act->title }}</h3>
                                <p class="small mb-2" style="color: var(--brand-accent);">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>Lugar: {{ $act->location ?? 'Zanjón de Garrapatero' }} · Hora: {{ $act->event_date ? $act->event_date->format('H:i A') : 'Por definir' }}
                                </p>
                                <p class="small mb-0" style="color: var(--text-muted);">{{ Str::limit($act->description, 130) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-lg-6">
                    <div class="glass-card p-4 d-flex gap-4 align-items-start">
                        <div class="p-3 text-center rounded-4 flex-shrink-0" style="background: rgba(181, 138, 69, 0.15); border: 1px solid var(--brand-accent); min-width: 80px;">
                            <span class="d-block text-uppercase small fw-bold" style="color: var(--brand-accent);">DIC</span>
                            <strong class="d-block fs-3" style="color: var(--brand-primary);">12</strong>
                        </div>
                        <div>
                            <span class="badge mb-2" style="background: var(--brand-primary); color: #ffffff;">Asamblea general</span>
                            <h3 class="h5 mb-2" style="color: var(--brand-primary);">Presentación de plataforma digital y socialización del censo</h3>
                            <p class="small mb-2" style="color: var(--brand-accent);">Lugar: Salón Comunitario El Palmar · Hora: 9:00 AM</p>
                            <p class="small mb-0" style="color: var(--text-muted);">Reunión formal con líderes veredales y comunidad general para concertar la ruta digital.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="glass-card p-4 d-flex gap-4 align-items-start">
                        <div class="p-3 text-center rounded-4 flex-shrink-0" style="background: rgba(23, 45, 77, 0.08); border: 1px solid var(--line); min-width: 80px;">
                            <span class="d-block text-uppercase small fw-bold" style="color: var(--brand-primary);">DIC</span>
                            <strong class="d-block fs-3" style="color: var(--brand-primary);">19</strong>
                        </div>
                        <div>
                            <span class="badge mb-2" style="background: var(--brand-accent); color: #ffffff;">Territorial</span>
                            <h3 class="h5 mb-2" style="color: var(--brand-primary);">Recorrido de protección y cuidado ambiental en cuenca hídrica</h3>
                            <p class="small mb-2" style="color: var(--brand-accent);">Lugar: Bajo San Francisco y Santa Lucía · Hora: 7:00 AM</p>
                            <p class="small mb-0" style="color: var(--text-muted);">Jornada de siembra comunitaria de especies nativas y verificación de linderos ancestrales.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('contacto') }}" class="btn btn-main">
                Proponer una actividad o novedad <i class="bi bi-arrow-up-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    #communityFullCalendar {
        min-height: 540px;
    }
    .fc .fc-toolbar-title {
        font-size: 1.25rem !important;
        font-weight: 800;
        color: #1e293b;
        text-transform: capitalize;
    }
    .fc .fc-button-primary {
        background-color: #2d51ad !important;
        border-color: #2d51ad !important;
        border-radius: 10px !important;
        font-weight: 600;
        text-transform: capitalize;
    }
    .fc .fc-button-primary:hover,
    .fc .fc-button-primary.fc-button-active {
        background-color: #1e3a8a !important;
        border-color: #1e3a8a !important;
    }
    .fc-event {
        border-radius: 8px !important;
        padding: 3px 6px !important;
        font-weight: 600 !important;
        cursor: pointer;
        border: none !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/es.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('communityFullCalendar');
    if (!calendarEl || typeof FullCalendar === 'undefined') return;

    const events = @json($calendarEvents);

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            list: 'Agenda'
        },
        events: events,
        eventClick: function (info) {
            const props = info.event.extendedProps || {};
            document.getElementById('fcEventTitle').textContent = info.event.title;
            document.getElementById('fcEventCategory').textContent = props.category || 'Evento';
            document.getElementById('fcEventDate').textContent = props.formatted_date || '';
            document.getElementById('fcEventLocation').textContent = props.location || 'Zanjón de Garrapatero';
            document.getElementById('fcEventStatus').textContent = props.status || 'Programada';
            document.getElementById('fcEventDescription').textContent = props.description || '';

            const modalEl = document.getElementById('fcEventModal');
            if (modalEl && window.bootstrap?.Modal) {
                bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        }
    });

    calendar.render();
});
</script>
@endpush
