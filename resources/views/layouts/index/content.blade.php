{{-- 1. Hero Principal Institucional con Efecto de Difusión (Arriba, como en el diseño) --}}
<section class="hero-diffusion-banner" aria-label="Bienvenida e identidad del Consejo Comunitario">
    <div class="hero-diffusion-orb orb-1" aria-hidden="true"></div>
    <div class="hero-diffusion-orb orb-2" aria-hidden="true"></div>
    <div class="wrap position-relative" style="z-index: 2;">
        <div class="row align-items-center g-4">
            <div class="col-lg-9">
                <div class="eyebrow-light mb-3">
                    <span class="dot-gold"></span> RAÍCES, TERRITORIO Y COMUNIDAD
                </div>
                <h1 class="hero-diffusion-title">
                    Nuestra historia nos une, <span class="hero-highlight">nuestro futuro</span> lo construimos juntos.
                </h1>
                <p class="hero-diffusion-lead">
                    Un espacio para compartir nuestra identidad, dar visibilidad al territorio y acercar la información comunitaria a todas las personas.
                </p>
                <div class="d-flex flex-wrap align-items-center gap-3 mt-4">
                    <a class="btn btn-main btn-water" href="{{ route('nosotros') }}">
                        Conoce el Consejo <i class="bi bi-arrow-up-right ms-2"></i>
                    </a>
                    <a class="btn btn-glass-light btn-water" href="{{ route('contacto') }}">
                        Solicitud de censo <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="hero-diffusion-tags mt-4">
                    <i class="bi bi-stars me-2" style="color: var(--brand-accent);"></i>
                    Identidad cultural &middot; Participación &middot; Información
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-flex justify-content-center">
                <div class="hero-diffusion-emblem">
                    <img src="{{ asset('logo/logo.webp') }}" alt="Escudo Consejo Comunitario Zanjón de Garrapatero" width="170" height="170">
                </div>
            </div>
        </div>
    </div>

    {{-- Efecto de Difusión Suave en la parte inferior hacia el blanco --}}
    <div class="hero-diffusion-wave" aria-hidden="true">
        <svg viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path d="M0,32 C360,85 1080,85 1440,32 L1440,90 L0,90 Z" fill="#ffffff"></path>
        </svg>
    </div>
</section>

{{-- Franja Inferior de Información del Consejo Comunitario --}}
<section class="hero-info-strip" aria-label="Información institucional de referencia">
    <div class="wrap">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 py-3">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span class="info-strip-label">IDENTIDAD COMUNITARIA</span>
                <span class="info-strip-pill"><strong class="me-1">3.131</strong> registros de referencia</span>
                <span class="info-strip-pill"><strong class="me-1">08</strong> territorios identificados</span>
            </div>
            <div class="d-flex align-items-center gap-2 small text-muted">
                <span class="triad-dot" style="background: #2D51AD;" title="#2D51AD"></span>
                <span class="triad-val-primary fw-semibold">#2D51AD</span>
                <span class="triad-dot border" style="background: #FFFFFF;" title="#FFFFFF"></span>
                <span class="triad-val-secondary fw-semibold">#FFFFFF</span>
                <span class="triad-dot" style="background: #F5CD6D;" title="#F5CD6D"></span>
                <span class="triad-val-accent fw-semibold">#F5CD6D</span>
            </div>
        </div>
    </div>
</section>

{{-- 2. Carrusel Multimedia (En la parte inferior del Hero, con bordes suaves) --}}
<section class="hero-carousel hero-carousel-below py-4" aria-label="Presentación y proyección multimedia destacada">
    <div class="wrap">
        <div id="inicioCarousel" class="carousel slide carousel-fade rounded-4 overflow-hidden shadow-lg" data-bs-ride="carousel" data-bs-interval="6500" data-bs-pause="hover" data-bs-wrap="true">
            {{-- Indicadores Glassmorphic Sincronizados con Bootstrap --}}
            @if(isset($carouselSlides) && $carouselSlides->isNotEmpty())
                <div class="carousel-indicators carousel-indicators-glass">
                    @foreach($carouselSlides as $idx => $slide)
                        <button type="button" data-bs-target="#inicioCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Proyección {{ $loop->iteration }}: {{ $slide->title }}"></button>
                    @endforeach
                </div>

                <div class="carousel-inner">
                    @foreach($carouselSlides as $slide)
                        @if($slide->isTextOnly())
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }} slide-no-media">
                                <div class="wrap w-100 position-relative">
                                    <div class="carousel-glass-panel">
                                        <span class="projection-badge mb-2">
                                            <span class="dot-live"></span> {{ $slide->badge_text ?? 'Comunicado · Identidad & Organización' }}
                                        </span>
                                        <h2 class="serif">{{ $slide->title }}</h2>
                                        @if($slide->subtitle)<p>{{ $slide->subtitle }}</p>@endif
                                        <div class="d-flex gap-3 flex-wrap align-items-center">
                                            @if($slide->primary_button_text)
                                                <a class="btn btn-main btn-water" href="{{ $slide->primary_button_url ?: route('nosotros') }}">
                                                    {{ $slide->primary_button_text }} <i class="bi bi-arrow-right ms-2"></i>
                                                </a>
                                            @endif
                                            @if($slide->secondary_button_text)
                                                <a class="btn btn-ghost btn-water" href="{{ $slide->secondary_button_url ?: route('actualidad') }}">
                                                    {{ $slide->secondary_button_text }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }} has-media">
                                <div class="carousel-media-container">
                                    @if($slide->isVideo())
                                        <video class="carousel-media" autoplay muted loop playsinline poster="{{ $slide->poster_url }}">
                                            <source src="{{ $slide->media_url }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ $slide->media_url }}" alt="{{ $slide->title }}" class="carousel-media" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                                    @endif
                                    <div class="carousel-overlay"></div>
                                </div>
                                <div class="wrap w-100 position-relative">
                                    <div class="carousel-glass-panel">
                                        <span class="projection-badge mb-2">
                                            <span class="dot-live"></span> {{ $slide->badge_text ?? ($slide->isVideo() ? 'Proyección · Video en Vivo' : 'Proyección · Imagen del Territorio') }}
                                        </span>
                                        <h2 class="serif">{{ $slide->title }}</h2>
                                        @if($slide->subtitle)<p>{{ $slide->subtitle }}</p>@endif
                                        <div class="d-flex gap-3 flex-wrap align-items-center">
                                            @if($slide->primary_button_text)
                                                <a class="btn btn-main btn-water" href="{{ $slide->primary_button_url ?: route('nosotros') }}">
                                                    {{ $slide->primary_button_text }} <i class="bi bi-arrow-right ms-2"></i>
                                                </a>
                                            @endif
                                            @if($slide->media_url)
                                                <button type="button" class="btn btn-projection btn-water" data-media-type="{{ $slide->isVideo() ? 'video' : 'image' }}" data-media-src="{{ $slide->media_url }}" data-media-title="{{ $slide->projection_title ?: $slide->title }}">
                                                    <i class="bi {{ $slide->isVideo() ? 'bi-play-circle-fill' : 'bi-fullscreen' }}" @if($slide->isVideo()) style="color: var(--brand-accent);" @endif></i> {{ $slide->isVideo() ? 'Proyectar Video' : 'Proyectar Imagen' }}
                                                </button>
                                            @endif
                                            @if($slide->secondary_button_text)
                                                <a class="btn btn-ghost btn-water" href="{{ $slide->secondary_button_url }}">
                                                    {{ $slide->secondary_button_text }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="carousel-indicators carousel-indicators-glass">
                    <button type="button" data-bs-target="#inicioCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Proyección 1: Territorio (Imagen)"></button>
                    <button type="button" data-bs-target="#inicioCarousel" data-bs-slide-to="1" aria-label="Proyección 2: Saberes (Video)"></button>
                    <button type="button" data-bs-target="#inicioCarousel" data-bs-slide-to="2" aria-label="Comunicado 3: Editorial (Sin video)"></button>
                    <button type="button" data-bs-target="#inicioCarousel" data-bs-slide-to="3" aria-label="Proyección 4: Guardia (Video)"></button>
                </div>

                <div class="carousel-inner">
                    {{-- Slide 1: Proyección de Imagen --}}
                    <div class="carousel-item active has-media">
                        <div class="carousel-media-container">
                            <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1920&q=85" alt="Paisaje natural del Zanjón de Garrapatero" class="carousel-media" loading="eager">
                            <div class="carousel-overlay"></div>
                        </div>
                        <div class="wrap w-100 position-relative">
                            <div class="carousel-glass-panel">
                                <span class="projection-badge mb-2">
                                    <span class="dot-live"></span> Proyección · Imagen del Territorio
                                </span>
                                <h2 class="serif">Territorio que se reconoce, comunidad que avanza.</h2>
                                <p>Una presencia digital soberana para conectar memoria, organización, cultura y participación comunitaria afrocolombiana.</p>
                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                    <a class="btn btn-main btn-water" href="{{ route('nosotros') }}">
                                        Conocer el Consejo <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                    <button type="button" class="btn btn-projection btn-water" data-media-type="image" data-media-src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1920&q=85" data-media-title="Paisaje y Cuencas Ancestrales — Zanjón de Garrapatero">
                                        <i class="bi bi-fullscreen"></i> Proyectar Imagen
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 2: Proyección de Video --}}
                    <div class="carousel-item has-media">
                        <div class="carousel-media-container">
                            <video class="carousel-media" autoplay muted loop playsinline poster="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1920&q=85">
                                <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4" type="video/mp4">
                            </video>
                            <div class="carousel-overlay"></div>
                        </div>
                        <div class="wrap w-100 position-relative">
                            <div class="carousel-glass-panel">
                                <span class="projection-badge mb-2">
                                    <span class="dot-live"></span> Proyección · Video en Vivo
                                </span>
                                <h2 class="serif">La voz de la comunidad también construye futuro.</h2>
                                <p>Noticias, asambleas veredales, proyectos productivos y canales para participar activamente en la vida del territorio.</p>
                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                    <a class="btn btn-main btn-water" href="{{ route('actividades') }}">
                                        Ver Actividades <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                    <button type="button" class="btn btn-projection btn-water" data-media-type="video" data-media-src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4" data-media-title="Relatos y Saberes Comunitarios en Movimiento">
                                        <i class="bi bi-play-circle-fill" style="color: var(--brand-accent);"></i> Proyectar Video
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 3: Sin Video ni Imagen --}}
                    <div class="carousel-item slide-no-media">
                        <div class="wrap w-100 position-relative">
                            <div class="carousel-glass-panel">
                                <span class="projection-badge mb-2">
                                    <span class="dot-live"></span> Comunicado · Identidad &amp; Organización
                                </span>
                                <h2 class="serif">Historias, acuerdos y memoria que construyen autonomía.</h2>
                                <p>Una presencia institucional transparente para coordinar proyectos productivos, censo étnico y el cuidado colectivo de nuestra gente.</p>
                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                    <a class="btn btn-main btn-water" href="{{ route('nosotros') }}">
                                        Conocer el Consejo <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                    <a class="btn btn-ghost btn-water" href="{{ route('actualidad') }}">
                                        Ver Comunicados <i class="bi bi-newspaper ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Slide 4: Proyección de Video --}}
                    <div class="carousel-item has-media">
                        <div class="carousel-media-container">
                            <video class="carousel-media" autoplay muted loop playsinline poster="https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1920&q=85">
                                <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" type="video/mp4">
                            </video>
                            <div class="carousel-overlay"></div>
                        </div>
                        <div class="wrap w-100 position-relative">
                            <div class="carousel-glass-panel">
                                <span class="projection-badge mb-2">
                                    <span class="dot-live"></span> Proyección · Guardia y Cuencas
                                </span>
                                <h2 class="serif">Cuidado integral de nuestras aguas y linderos ancestrales.</h2>
                                <p>Recorridos de prevención pacífica, cuidado ambiental y autonomía colectiva en las 8 veredas articuladas.</p>
                                <div class="d-flex gap-3 flex-wrap align-items-center">
                                    <a class="btn btn-main btn-water" href="{{ route('territorio') }}">
                                        Explorar Veredas <i class="bi bi-compass ms-2"></i>
                                    </a>
                                    <button type="button" class="btn btn-projection btn-water" data-media-type="video" data-media-src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" data-media-title="Custodia del Territorio y Fuentes Hídricas">
                                        <i class="bi bi-play-circle-fill" style="color: var(--brand-accent);"></i> Proyectar Video
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Controles Glassmorphic Circulares Flotantes --}}
            <button class="carousel-control-glass prev" type="button" data-bs-target="#inicioCarousel" data-bs-slide="prev" aria-label="Diapositiva anterior">
                <i class="bi bi-chevron-left" aria-hidden="true"></i>
            </button>
            <button class="carousel-control-glass next" type="button" data-bs-target="#inicioCarousel" data-bs-slide="next" aria-label="Diapositiva siguiente">
                <i class="bi bi-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 3. Nuestra Esencia (Quiénes Somos, Misión y Visión — Estilo Vista2) --}}
<section class="section section-light" id="quienes-somos">
    <div class="wrap">
        <div class="row align-items-end g-4 mb-5">
            <div class="col-lg-6">
                <div class="kicker">01 · Nuestra esencia</div>
                <h2 class="section-title mb-0">Un consejo, una historia<br><span class="text-brown">que sigue creciendo.</span></h2>
            </div>
            <div class="col-lg-6">
                <p class="section-copy mb-3">
                    El Consejo Comunitario Zanjón de Garrapatero es una organización territorial afrocolombiana que articula comunidad, cultura, participación y cuidado integral del territorio.
                </p>
                <a href="{{ route('nosotros') }}" class="read-more">Conocer historia, autoridades y gobernanza <i class="bi bi-arrow-up-right"></i></a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <article class="vista2-feature-card">
                    <div class="vista2-feature-icon"><i class="bi bi-stars"></i></div>
                    <span class="vista2-feature-number">01 / Institucional</span>
                    <h3 class="fw-bold mb-2">Quiénes somos</h3>
                    <p class="text-muted small mb-4" style="line-height: 1.7;">
                        Autoridad étnica y comunitaria comprometida con la defensa de la identidad afro, la memoria ancestral y el bienestar de las 8 veredas articuladas.
                    </p>
                    <span class="vista2-feature-line"></span>
                </article>
            </div>
            <div class="col-md-4">
                <article class="vista2-feature-card vista2-feature-card--brown">
                    <div class="vista2-feature-icon"><i class="bi bi-bullseye"></i></div>
                    <span class="vista2-feature-number">02 / Propósito</span>
                    <h3 class="fw-bold mb-2">Misión</h3>
                    <p class="text-muted small mb-4" style="line-height: 1.7;">
                        Proteger los derechos colectivos reconocidos en la Ley 70 de 1993, resguardar las cuencas hídricas y fortalecer la participación transparente sin intermediarios.
                    </p>
                    <span class="vista2-feature-line"></span>
                </article>
            </div>
            <div class="col-md-4">
                <article class="vista2-feature-card vista2-feature-card--gold">
                    <div class="vista2-feature-icon"><i class="bi bi-arrow-up-right-circle"></i></div>
                    <span class="vista2-feature-number">03 / Proyección</span>
                    <h3 class="fw-bold mb-2">Visión</h3>
                    <p class="text-muted small mb-4" style="line-height: 1.7;">
                        Consolidar un territorio soberano, conectado digitalmente y próspero, donde las nuevas generaciones crezcan orgullosas de sus raíces y autonomía colectiva.
                    </p>
                    <span class="vista2-feature-line"></span>
                </article>
            </div>
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 4. Estructura Comunitaria (Organizados para avanzar juntos) --}}
<section class="section" id="organizacion">
    <div class="wrap">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="kicker" style="color: #6d4c3d;">02 · Estructura comunitaria</div>
                <h2 class="section-title mb-3">Organizados para <span class="text-brown">avanzar juntos.</span></h2>
                <p class="section-copy mb-4">
                    La gobernanza del Consejo Comunitario se articula mediante instancias elegidas democráticamente en asamblea general para representar y cuidar el territorio.
                </p>
                <a href="{{ route('nosotros') }}" class="btn btn-main btn-water">
                    Ver Junta y Autoridades <i class="bi bi-arrow-up-right ms-2"></i>
                </a>
            </div>
            <div class="col-lg-7">
                <div class="vista2-org-item">
                    <span class="vista2-org-index">01</span>
                    <div class="flex-grow-1">
                        <h3 class="h6 fw-bold mb-0">Junta de Gobierno</h3>
                        <p class="small text-muted mb-0">Gobierno propio y representación comunitaria</p>
                    </div>
                    <i class="bi bi-arrow-up-right text-muted"></i>
                </div>
                <div class="vista2-org-item">
                    <span class="vista2-org-index">02</span>
                    <div class="flex-grow-1">
                        <h3 class="h6 fw-bold mb-0">Representación Legal</h3>
                        <p class="small text-muted mb-0">Gestión jurídica y representación institucional</p>
                    </div>
                    <i class="bi bi-arrow-up-right text-muted"></i>
                </div>
                <div class="vista2-org-item">
                    <span class="vista2-org-index">03</span>
                    <div class="flex-grow-1">
                        <h3 class="h6 fw-bold mb-0">Presidentes de JAC</h3>
                        <p class="small text-muted mb-0">Articulación y participación directa en cada vereda</p>
                    </div>
                    <i class="bi bi-arrow-up-right text-muted"></i>
                </div>
                <div class="vista2-org-item">
                    <span class="vista2-org-index">04</span>
                    <div class="flex-grow-1">
                        <h3 class="h6 fw-bold mb-0">Guardia Cimarrona</h3>
                        <p class="small text-muted mb-0">Custodia pacífica, prevención y cuidado de linderos</p>
                    </div>
                    <i class="bi bi-arrow-up-right text-muted"></i>
                </div>
                <div class="vista2-org-item mb-0">
                    <span class="vista2-org-index">05</span>
                    <div class="flex-grow-1">
                        <h3 class="h6 fw-bold mb-0">Equipo Administrativo y de Campo</h3>
                        <p class="small text-muted mb-0">Apoyo técnico al censo étnico y proyectos comunitarios</p>
                    </div>
                    <i class="bi bi-arrow-up-right text-muted"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 5. Nuestro Territorio (Mapa Conceptual + Veredas Dinámicas) --}}
<section class="section section-light" id="territorio">
    <div class="wrap">
        <div class="row g-5 align-items-center mb-5">
            <div class="col-lg-5">
                <div class="kicker">03 · Nuestro territorio</div>
                <h2 class="section-title mb-3">Un mapa para<br><span class="text-brown">reconocer lo nuestro.</span></h2>
                <p class="section-copy mb-4">
                    Veredas, fincas, reservas hídricas y espacios comunitarios que tejen la vida, la memoria y la soberanía del Zanjón de Garrapatero.
                </p>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="vista2-chip">Veredas</span>
                    <span class="vista2-chip">Fincas</span>
                    <span class="vista2-chip">Reservas</span>
                    <span class="vista2-chip">Espacios comunitarios</span>
                </div>
                <a href="{{ route('territorio') }}" class="btn btn-ghost btn-water">
                    Explorar todas las veredas <i class="bi bi-compass ms-1"></i>
                </a>
            </div>
            <div class="col-lg-7">
                <div class="vista2-map-demo">
                    <div class="vista2-map-demo__header">
                        <strong style="color: #1e3a8a;">Territorio y lugares de interés</strong>
                        <span class="badge bg-light text-secondary border">CARTOGRAFÍA COMUNITARIA</span>
                    </div>
                    <svg viewBox="0 0 600 320" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-100 d-block">
                        <defs>
                            <pattern id="mapDotsHome" width="22" height="22" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.5" fill="#afc7d7"/></pattern>
                            <linearGradient id="regionFillHome" x1="40" y1="0" x2="430" y2="320" gradientUnits="userSpaceOnUse"><stop stop-color="#e5d4be"/><stop offset="1" stop-color="#be9a74"/></linearGradient>
                        </defs>
                        <rect width="600" height="320" fill="#ecf5fa"/>
                        <rect width="600" height="320" fill="url(#mapDotsHome)"/>
                        <path d="M120 76 211 31l74 31 96-20 87 56 22 82-64 51-34 55-105-24-59 27-89-37-58-83Z" fill="url(#regionFillHome)" stroke="#8f6a4d" stroke-width="2" stroke-dasharray="8 7"/>
                        <path d="m0 220 128-49 89 23 112-95 116 24 155-73" stroke="#90bfd8" stroke-width="15" stroke-linecap="round" fill="none"/>
                        <path d="m0 220 128-49 89 23 112-95 116 24 155-73" stroke="#d8ecf7" stroke-width="6" stroke-linecap="round" fill="none"/>
                        <g fill="#2d51ad" stroke="#ffffff" stroke-width="4">
                            <circle cx="212" cy="130" r="12"/>
                            <circle cx="347" cy="96" r="12"/>
                            <circle cx="299" cy="215" r="12"/>
                        </g>
                        <circle cx="396" cy="190" r="12" fill="#f5cd6d" stroke="#ffffff" stroke-width="4"/>
                        <g fill="#1e3a8a" font-family="Manrope, sans-serif" font-size="13" font-weight="800">
                            <text x="178" y="108">El Palmar</text>
                            <text x="354" y="76">Reservas</text>
                            <text x="256" y="254">Santa Lucía</text>
                            <text x="404" y="224">La Toma</text>
                        </g>
                    </svg>
                    <div class="vista2-map-demo__footer">
                        <span><i class="bi bi-circle-fill text-primary me-1" style="font-size: 0.6rem;"></i> Veredas y sectores</span>
                        <span><i class="bi bi-circle-fill text-warning me-1" style="font-size: 0.6rem;"></i> Espacios comunitarios</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mini-grid">
            @if(isset($territories) && $territories->isNotEmpty())
                @foreach($territories->take(4) as $t)
                    <article class="territory">
                        @if($t->sector_badge)
                            <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(245, 205, 109, 0.25); color: #ffffff; border: 1px solid rgba(245, 205, 109, 0.5); font-size: 0.68rem; letter-spacing: 0.08em; text-transform: uppercase;">
                                <i class="bi bi-geo-alt-fill me-1"></i> {{ $t->sector_badge }}
                            </span>
                        @endif
                        <h3>{{ $t->name }}</h3>
                        <p>{{ Str::limit($t->description, 85) }}</p>
                        <a href="{{ route('territorio') }}" class="read-more text-white">Conocer más <i class="bi bi-arrow-right"></i></a>
                    </article>
                @endforeach
            @else
                <article class="territory">
                    <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(245, 205, 109, 0.25); color: #ffffff; border: 1px solid rgba(245, 205, 109, 0.5); font-size: 0.68rem;">SECTOR CENTRAL</span>
                    <h3>El Palmar</h3>
                    <p>La mayor concentración de registros en la base de referencia comunitaria.</p>
                    <a href="{{ route('territorio') }}" class="read-more text-white">Conocer más <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="territory">
                    <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(245, 205, 109, 0.25); color: #ffffff; border: 1px solid rgba(245, 205, 109, 0.5); font-size: 0.68rem;">CUENCA ALTA</span>
                    <h3>Santa Lucía</h3>
                    <p>Comunidad, recorridos y memoria que conectan el paisaje ancestral.</p>
                    <a href="{{ route('territorio') }}" class="read-more text-white">Conocer más <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="territory">
                    <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(245, 205, 109, 0.25); color: #ffffff; border: 1px solid rgba(245, 205, 109, 0.5); font-size: 0.68rem;">RESERVA HÍDRICA</span>
                    <h3>La Toma</h3>
                    <p>Territorio y organización como ejercicio permanente de autonomía.</p>
                    <a href="{{ route('territorio') }}" class="read-more text-white">Conocer más <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="territory">
                    <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(245, 205, 109, 0.25); color: #ffffff; border: 1px solid rgba(245, 205, 109, 0.5); font-size: 0.68rem;">FAMILIAS</span>
                    <h3>Mazamorrero</h3>
                    <p>Una entrada para reconocer a las familias campesinas y sus historias.</p>
                    <a href="{{ route('territorio') }}" class="read-more text-white">Conocer más <i class="bi bi-arrow-right"></i></a>
                </article>
            @endif
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 6. Proyectos, Iniciativas y Agenda --}}
<section class="section manifesto" id="proyectos">
    <div class="wrap">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="section-header mb-0">
                <div class="kicker">04 · Proyectos e iniciativas</div>
                <h2 class="section-title">Ideas que fortalecen <span class="text-brown">el territorio.</span></h2>
                <p class="section-copy">Asambleas, mingas ambientales, memoria cultural y gestión comunitaria en calendario.</p>
            </div>
            <a href="{{ route('actividades') }}" class="btn btn-main btn-water">
                Ver Calendario de Eventos <i class="bi bi-calendar-event ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <article class="vista2-feature-card">
                    <div class="vista2-feature-icon"><i class="bi bi-tree-fill"></i></div>
                    <span class="vista2-feature-number">Línea Estratégica</span>
                    <h3 class="fw-bold mb-2">Territorio y desarrollo</h3>
                    <p class="text-muted small mb-3">Iniciativas productivas, cuidado de cuencas y protección ambiental en nuestras veredas.</p>
                    <a href="{{ route('actividades') }}" class="read-more mt-auto">Ver agenda <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
            <div class="col-md-4">
                <article class="vista2-feature-card vista2-feature-card--brown">
                    <div class="vista2-feature-icon"><i class="bi bi-music-note-beamed"></i></div>
                    <span class="vista2-feature-number">Línea Cultural</span>
                    <h3 class="fw-bold mb-2">Cultura e identidad</h3>
                    <p class="text-muted small mb-3">Divulgación de saberes ancestrales, cantos tradicionales, cimarrona y memoria viva afro.</p>
                    <a href="{{ route('galeria') }}" class="read-more mt-auto">Explorar galería <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
            <div class="col-md-4">
                <article class="vista2-feature-card vista2-feature-card--gold">
                    <div class="vista2-feature-icon"><i class="bi bi-people-fill"></i></div>
                    <span class="vista2-feature-number">Línea Organizativa</span>
                    <h3 class="fw-bold mb-2">Participación comunitaria</h3>
                    <p class="text-muted small mb-3">Asambleas generales, censo territorial digital, avales y atención directa a las familias.</p>
                    <a href="{{ route('contacto') }}" class="read-more mt-auto">Solicitar censo <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 7. Actualidad del Consejo con Filtros por Categoría --}}
<section class="section section-light" id="actualidad">
    <div class="wrap">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="section-header mb-0">
                <div class="kicker">05 · Actualidad del Consejo</div>
                <h2 class="section-title">La comunidad <span class="text-brown">está informada.</span></h2>
            </div>
            <a href="{{ route('actualidad') }}" class="btn btn-main btn-water">
                Ver todas las noticias <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4" id="homePubTabs" role="tablist">
            <button type="button" class="vista2-tab-btn is-active" data-pub-filter="all">Todas</button>
            <button type="button" class="vista2-tab-btn" data-pub-filter="noticia">Noticias</button>
            <button type="button" class="vista2-tab-btn" data-pub-filter="evento">Eventos</button>
            <button type="button" class="vista2-tab-btn" data-pub-filter="comunicado">Comunicados</button>
        </div>

        <div class="row g-4" id="homePubGrid">
            @if(isset($publications) && $publications->isNotEmpty())
                @foreach($publications->take(3) as $pub)
                    <div class="col-md-4 home-pub-card" data-pub-type="{{ strtolower($pub->category) }}">
                        <article class="news-card">
                            <span class="news-date">{{ $pub->category }} · {{ $pub->published_at ? $pub->published_at->format('d/m/Y') : date('Y') }}</span>
                            <h3>{{ $pub->title }}</h3>
                            <p>{{ $pub->summary ?: Str::limit($pub->content, 110) }}</p>
                            <a class="read-more" href="{{ route('actualidad') }}">Leer más <i class="bi bi-arrow-up-right"></i></a>
                        </article>
                    </div>
                @endforeach
            @else
                <div class="col-md-4 home-pub-card" data-pub-type="evento">
                    <article class="news-card">
                        <span class="news-date">Evento · {{ date('F Y') }}</span>
                        <h3>Presentación formal ante la asamblea</h3>
                        <p>Un encuentro para presentar la aplicación, sus objetivos y el camino de apropiación comunitaria.</p>
                        <a class="read-more" href="{{ route('actualidad') }}">Leer más <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
                <div class="col-md-4 home-pub-card" data-pub-type="noticia">
                    <article class="news-card">
                        <span class="news-date">Noticia · Censo</span>
                        <h3>Hacia un registro comunitario ampliado</h3>
                        <p>La plataforma permite solicitar registro, adjuntar documentos y validar pertenencia territorial.</p>
                        <a class="read-more" href="{{ route('actualidad') }}">Conocer proceso <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
                <div class="col-md-4 home-pub-card" data-pub-type="comunicado">
                    <article class="news-card">
                        <span class="news-date">Comunicado · Memoria</span>
                        <h3>Noticias, eventos y comunicados</h3>
                        <p>Un archivo público y vivo para que la comunidad encuentre información verificada en un solo lugar.</p>
                        <a class="read-more" href="{{ route('actualidad') }}">Ver comunicados <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 8. Galería Visual --}}
<section class="section">
    <div class="wrap">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="section-header mb-0">
                <div class="kicker">06 · Galería</div>
                <h2 class="section-title">Rostros, caminos y memoria.</h2>
                <p class="section-copy">Registro visual del territorio con fotografías reales de nuestra gente y sus paisajes.</p>
            </div>
            <a href="{{ route('galeria') }}" class="btn btn-main btn-water">
                Ver galería completa <i class="bi bi-images ms-1"></i>
            </a>
        </div>
        <div class="gallery-grid">
            @if(isset($featuredMedia) && $featuredMedia->isNotEmpty())
                @foreach($featuredMedia->take(5) as $fm)
                    <figure class="gallery-item">
                        <img src="{{ $fm->file_url }}" alt="{{ $fm->title }}" loading="lazy">
                        <figcaption>{{ $fm->caption ?: $fm->title }}</figcaption>
                    </figure>
                @endforeach
            @else
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=85" alt="Paisaje verde de territorio comunitario" loading="lazy">
                    <figcaption>Territorio que se cuida</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=800&q=85" alt="Personas reunidas en comunidad" loading="lazy">
                    <figcaption>Encuentro y participación</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=85" alt="Actividad cultural con público" loading="lazy">
                    <figcaption>Cultura viva</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1474511320723-9a56873867b5?auto=format&fit=crop&w=800&q=85" alt="Camino entre árboles" loading="lazy">
                    <figcaption>Memoria del paisaje</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=800&q=85" alt="Equipo dialogando alrededor de una mesa" loading="lazy">
                    <figcaption>Organización y futuro</figcaption>
                </figure>
            @endif
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 9. Panel de Contacto Institucional ("Conversemos") --}}
<section class="section pt-0" id="contacto">
    <div class="wrap">
        <div class="vista2-contact-panel">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <span class="eyebrow-light mb-2"><span class="dot-gold"></span> CONVERSEMOS</span>
                    <h2 class="fw-bold text-white mb-3" style="font-size: clamp(1.85rem, 3.2vw, 2.75rem);">Un espacio abierto<br>para nuestra comunidad.</h2>
                    <p class="text-white-50 mb-4" style="max-width: 480px; line-height: 1.7;">
                        Comunícate con la Junta de Gobierno, envía solicitudes de censo o consulta canales oficiales de atención territorial.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a class="btn btn-main btn-water" href="{{ route('contacto') }}">
                            Ir a Contacto y Censo <i class="bi bi-arrow-up-right ms-1"></i>
                        </a>
                        <a class="btn btn-glass-light btn-water" href="#mainContent">
                            Volver al inicio <i class="bi bi-arrow-up ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex flex-column gap-3">
                        <div class="vista2-contact-card">
                            <span class="fs-4 text-warning"><i class="bi bi-envelope-fill"></i></span>
                            <div>
                                <strong class="d-block text-white">Contacto institucional</strong>
                                <span class="small text-white-50">comunicaciones@consejozanjondegarrapatero.org</span>
                            </div>
                        </div>
                        <div class="vista2-contact-card">
                            <span class="fs-4 text-warning"><i class="bi bi-geo-alt-fill"></i></span>
                            <div>
                                <strong class="d-block text-white">Sede Territorial</strong>
                                <span class="small text-white-50">Zanjón de Garrapatero · Departamento del Cauca, Colombia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('#homePubTabs [data-pub-filter]');
    const cards = document.querySelectorAll('#homePubGrid .home-pub-card');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            tabButtons.forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
            const filter = btn.dataset.pubFilter;
            cards.forEach(card => {
                const type = card.dataset.pubType || '';
                const show = filter === 'all' || type.includes(filter);
                card.classList.toggle('d-none', !show);
            });
        });
    });
});
</script>
@endpush
