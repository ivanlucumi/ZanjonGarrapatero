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

        {{-- Calendario de Próximas Actividades --}}
        <div class="section-header">
            <span class="kicker">Calendario territorial</span>
            <h2 class="section-title serif">Encuentros y convocatorias {{ date('Y') }}</h2>
            <p class="section-copy">
                Participa en las fechas programadas en las distintas veredas del Consejo.
            </p>
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
