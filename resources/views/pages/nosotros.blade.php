@extends('layouts.index')

@section('title', 'Nosotros | Consejo Comunitario Zanjón de Garrapatero')
@section('meta_description', 'Conoce la historia, gobierno propio, principios de autonomía y estructura del Consejo Comunitario Zanjón de Garrapatero en el Cauca.')

@section('content')
<div class="page-header">
    <div class="wrap">
        <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            <span>/</span>
            <span>Nosotros</span>
        </nav>
        <span class="glass-pill glass-pill-gold mb-2">01 · Identidad y gobernanza</span>
        <h1 class="page-header-title serif">Gobierno propio, memoria y futuro colectivo.</h1>
        <p class="page-header-subtitle">
            El Consejo Comunitario Zanjón de Garrapatero es una autoridad territorial étnica afrocolombiana que articula familias, cultura, autonomía y el cuidado integral de nuestra tierra ancestral.
        </p>
    </div>
</div>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

<section class="section">
    <div class="wrap">
        <div class="row g-4 align-items-stretch mb-5">
            <div class="col-lg-7">
                <div class="glass-card p-4 p-lg-5 h-100">
                    <span class="glass-pill glass-pill-blue mb-3">Nuestra mirada</span>
                    <h2 class="serif mt-3 mb-4 display-6" style="color: var(--brand-primary);">“La organización nace de la gente y vuelve a la gente.”</h2>
                    <p class="section-copy">
                        Construimos una presencia institucional abierta y transparente. Creemos firmemente que el territorio se cuida entre todos, protegiendo tanto las fuentes hídricas como la dignidad de cada familia afro que habita y cultiva la tierra.
                    </p>
                    <p class="section-copy">
                        Nuestra prioridad es la protección de los derechos étnicos y colectivos reconocidos en la Ley 70 de 1993, garantizando que las nuevas generaciones crezcan con orgullo de sus raíces y oportunidades reales de desarrollo territorial.
                    </p>
                    <div class="stat-row mt-4">
                        <div class="stat">
                            <b>100%</b>
                            <span>participación comunitaria</span>
                        </div>
                        <div class="stat">
                            <b>08</b>
                            <span>veredas articuladas</span>
                        </div>
                        <div class="stat">
                            <b>Ley 70</b>
                            <span>marco étnico de 1993</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="glass-card p-4 p-lg-5 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <span class="glass-pill glass-pill-gold mb-3">Símbolo vivo</span>
                        <div class="text-center my-4">
                            <img src="{{ asset('logo/logo.webp') }}" alt="Identidad Consejo Comunitario Zanjón de Garrapatero" class="img-fluid rounded-4" style="max-height: 180px; filter: drop-shadow(0 15px 30px rgba(23,45,77,0.15));">
                        </div>
                        <h3 class="h4 mb-2" style="color: var(--brand-primary);">Tríada cromática institucional</h3>
                        <p class="small" style="color: var(--text-muted);">
                            Nuestra identidad se expresa en la solidez del río y el territorio (<strong style="color:var(--brand-primary);">#172D4D</strong>), la dignidad, la memoria y la transparencia (<strong style="color:var(--brand-accent);">#F4F1EA</strong>), y el oro ancestral, la cosecha y la sabiduría de nuestros mayores (<strong style="color:var(--brand-accent);">#B58A45</strong>).
                        </p>
                    </div>
                    <div class="pt-3 border-top" style="border-color: var(--line) !important;">
                        <a href="{{ route('territorio') }}" class="btn btn-main w-100 justify-content-center">
                            Explorar nuestro territorio <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
        <div class="relief-divider" aria-hidden="true"></div>

        {{-- Autoridades Tradicionales y Junta de Gobierno (Dinámico) --}}
        @if(isset($boardMembers) && $boardMembers->isNotEmpty())
        <div class="section-header">
            <span class="kicker">Gobernanza Tradicional</span>
            <h2 class="section-title serif">Autoridades y Equipo Directivo</h2>
            <p class="section-copy">
                Líderes, sabedores y comuneros elegidos por la asamblea general para la representación colectiva, la guardia cimarrona y la defensa territorial.
            </p>
        </div>

        <div class="row g-4 mb-5">
            @foreach($boardMembers as $member)
                <div class="col-md-6 col-lg-3">
                    <article class="glass-card p-4 h-100 d-flex flex-column text-center">
                        <div class="mb-3 mx-auto position-relative" style="width: 96px; height: 96px;">
                            @if($member->photo_url)
                                <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="rounded-circle w-100 h-100 object-fit-cover" style="border: 3px solid var(--brand-accent); box-shadow: 0 8px 20px rgba(23,45,77,0.15);">
                            @else
                                <div class="rounded-circle w-100 h-100 d-flex align-items-center justify-content-center text-white fw-bold fs-4" style="background: linear-gradient(135deg, var(--brand-primary), var(--brand-accent)); border: 3px solid var(--brand-accent);">
                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <span class="glass-pill glass-pill-gold mb-2 mx-auto small">{{ $member->committee ?? 'Gobierno Propio' }}</span>
                        <h3 class="h5 mb-1" style="color: var(--brand-primary);">{{ $member->name }}</h3>
                        <p class="small fw-semibold mb-2" style="color: var(--brand-accent);">{{ $member->role_title }}</p>
                        @if($member->bio)
                            <p class="small text-muted mb-3 flex-grow-1" style="line-height: 1.5;">{{ \Illuminate\Support\Str::limit($member->bio, 120) }}</p>
                        @endif
                        @if($member->email || $member->phone)
                            <div class="pt-2 border-top small text-muted d-flex justify-content-center gap-3 mt-auto" style="border-color: var(--line) !important;">
                                @if($member->email)
                                    <a href="mailto:{{ $member->email }}" class="text-decoration-none" title="{{ $member->email }}" style="color: var(--brand-primary);"><i class="bi bi-envelope"></i></a>
                                @endif
                                @if($member->phone)
                                    <a href="tel:{{ $member->phone }}" class="text-decoration-none" title="{{ $member->phone }}" style="color: var(--brand-primary);"><i class="bi bi-telephone"></i></a>
                                @endif
                            </div>
                        @endif
                    </article>
                </div>
            @endforeach
        </div>

        <div class="relief-divider" aria-hidden="true"></div>
        @endif

        {{-- Pilares de Gobierno Propio --}}
        <div class="section-header">
            <span class="kicker">Pilares fundamentales</span>
            <h2 class="section-title serif">¿Cómo nos organizamos?</h2>
            <p class="section-copy">
                La gobernanza del Consejo se sostiene en cuatro comités de trabajo coordinados democráticamente en asamblea general.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <article class="glass-card p-4 h-100">
                    <div class="d-inline-flex p-3 rounded-4 mb-3" style="background: rgba(181, 138, 69, 0.15); color: var(--brand-accent);">
                        <i class="bi bi-diagram-3 fs-3"></i>
                    </div>
                    <h3 class="h5 mb-2" style="color: var(--brand-primary);">Junta de gobierno</h3>
                    <p class="small mb-0" style="color: var(--text-muted); line-height: 1.6;">
                        Instancia directiva elegida en asamblea encargada de representar legal y comunitariamente los intereses colectivos del Consejo.
                    </p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="glass-card p-4 h-100">
                    <div class="d-inline-flex p-3 rounded-4 mb-3" style="background: rgba(23, 45, 77, 0.08); color: var(--brand-primary); border: 1px solid var(--line);">
                        <i class="bi bi-shield-check fs-3"></i>
                    </div>
                    <h3 class="h5 mb-2" style="color: var(--brand-primary);">Guardia Cimarrona</h3>
                    <p class="small mb-0" style="color: var(--text-muted); line-height: 1.6;">
                        Custodios pacíficos del territorio, garantes de la armonía comunitaria, la convivencia y el cuidado de los linderos ancestrales.
                    </p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="glass-card p-4 h-100">
                    <div class="d-inline-flex p-3 rounded-4 mb-3" style="background: rgba(181, 138, 69, 0.15); color: var(--brand-accent);">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                    <h3 class="h5 mb-2" style="color: var(--brand-primary);">Comité de mujeres</h3>
                    <p class="small mb-0" style="color: var(--text-muted); line-height: 1.6;">
                        Liderazgo en soberanía alimentaria, saberes de medicina tradicional, cuidado de la infancia y transmisión de la memoria oral.
                    </p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="glass-card p-4 h-100">
                    <div class="d-inline-flex p-3 rounded-4 mb-3" style="background: rgba(23, 45, 77, 0.08); color: var(--brand-primary); border: 1px solid var(--line);">
                        <i class="bi bi-tree fs-3"></i>
                    </div>
                    <h3 class="h5 mb-2" style="color: var(--brand-primary);">Tierras y ambiente</h3>
                    <p class="small mb-0" style="color: var(--text-muted); line-height: 1.6;">
                        Supervisión ambiental, monitoreo de cuencas, fomento agroecológico y resolución concertada de deslindes familiares.
                    </p>
                </article>
            </div>
        </div>

        {{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
        <div class="relief-divider" aria-hidden="true"></div>

        {{-- Banner de Acción --}}
        <div class="glass-card p-4 p-lg-5 mt-5 text-center" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(181, 138, 69, 0.15)); border: 1px solid rgba(181, 138, 69, 0.35);">
            <h3 class="serif display-6 mb-3" style="color: var(--brand-primary);">¿Deseas participar en las asambleas o proponer un proyecto?</h3>
            <p class="mx-auto" style="max-width: 620px; color: var(--text-muted);">
                El Consejo Comunitario es de puertas abiertas. Escríbenos o consulta el calendario de asambleas para participar en la toma de decisiones.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
                <a href="{{ route('actividades') }}" class="btn btn-main">Ver actividades y asambleas <i class="bi bi-calendar-event ms-1"></i></a>
                <a href="{{ route('contacto') }}" class="btn btn-ghost">Canal de contacto <i class="bi bi-chat-dots ms-1"></i></a>
            </div>
        </div>
    </div>
</section>
@endsection
