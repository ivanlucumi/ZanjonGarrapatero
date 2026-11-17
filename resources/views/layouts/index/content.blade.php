{{-- 1. Hero Carousel Multimedia (Imágenes y Videos para Proyección) --}}
<section class="hero-carousel" aria-label="Presentación y proyección multimedia destacada">
    <div id="inicioCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6500" data-bs-pause="hover" data-bs-wrap="true">
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
                                            <a class="btn btn-main" href="{{ $slide->primary_button_url ?: route('nosotros') }}">
                                                {{ $slide->primary_button_text }} <i class="bi bi-arrow-right ms-2"></i>
                                            </a>
                                        @endif
                                        @if($slide->secondary_button_text)
                                            <a class="btn btn-ghost" href="{{ $slide->secondary_button_url ?: route('actualidad') }}">
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
                                    @if($loop->first)
                                        <h1 class="serif">{{ $slide->title }}</h1>
                                    @else
                                        <h2 class="serif">{{ $slide->title }}</h2>
                                    @endif
                                    @if($slide->subtitle)<p>{{ $slide->subtitle }}</p>@endif
                                    <div class="d-flex gap-3 flex-wrap align-items-center">
                                        @if($slide->primary_button_text)
                                            <a class="btn btn-main" href="{{ $slide->primary_button_url ?: route('nosotros') }}">
                                                {{ $slide->primary_button_text }} <i class="bi bi-arrow-right ms-2"></i>
                                            </a>
                                        @endif
                                        @if($slide->media_url)
                                            <button type="button" class="btn-projection" data-media-type="{{ $slide->isVideo() ? 'video' : 'image' }}" data-media-src="{{ $slide->media_url }}" data-media-title="{{ $slide->projection_title ?: $slide->title }}">
                                                <i class="bi {{ $slide->isVideo() ? 'bi-play-circle-fill' : 'bi-fullscreen' }}" @if($slide->isVideo()) style="color: var(--brand-accent);" @endif></i> {{ $slide->isVideo() ? 'Proyectar Video' : 'Proyectar Imagen' }}
                                            </button>
                                        @endif
                                        @if($slide->secondary_button_text)
                                            <a class="btn btn-ghost" href="{{ $slide->secondary_button_url }}">
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
                {{-- Slide 1: Proyección de Imagen (Territorio y Paisaje Ancestral - Fondo Visible) --}}
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
                            <h1 class="serif">Territorio que se reconoce, comunidad que avanza.</h1>
                            <p>Una presencia digital soberana para conectar memoria, organización, cultura y participación comunitaria afrocolombiana.</p>
                            <div class="d-flex gap-3 flex-wrap align-items-center">
                                <a class="btn btn-main" href="{{ route('nosotros') }}">
                                    Conocer el Consejo <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                                <button type="button" class="btn-projection" data-media-type="image" data-media-src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1920&q=85" data-media-title="Paisaje y Cuencas Ancestrales — Zanjón de Garrapatero">
                                    <i class="bi bi-fullscreen"></i> Proyectar Imagen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Slide 2: Proyección de Video (Relatos, Saberes y Vida Comunitaria - Fondo Visible) --}}
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
                                <a class="btn btn-main" href="{{ route('actividades') }}">
                                    Ver Actividades <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                                <button type="button" class="btn-projection" data-media-type="video" data-media-src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4" data-media-title="Relatos y Saberes Comunitarios en Movimiento">
                                    <i class="bi bi-play-circle-fill" style="color: var(--brand-accent);"></i> Proyectar Video
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Slide 3: Sin Video ni Imagen (Editorial / Informativo con texto en tono azul del diseño) --}}
                <div class="carousel-item slide-no-media">
                    <div class="wrap w-100 position-relative">
                        <div class="carousel-glass-panel">
                            <span class="projection-badge mb-2">
                                <span class="dot-live"></span> Comunicado · Identidad &amp; Organización
                            </span>
                            <h2 class="serif">Historias, acuerdos y memoria que construyen autonomía.</h2>
                            <p>Una presencia institucional transparente para coordinar proyectos productivos, censo étnico y el cuidado colectivo de nuestra gente.</p>
                            <div class="d-flex gap-3 flex-wrap align-items-center">
                                <a class="btn btn-main" href="{{ route('nosotros') }}">
                                    Conocer el Consejo <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                                <a class="btn btn-ghost" href="{{ route('actualidad') }}">
                                    Ver Comunicados <i class="bi bi-newspaper ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Slide 4: Proyección de Video (Guardia Cimarrona y Aguas - Fondo Visible) --}}
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
                                <a class="btn btn-main" href="{{ route('territorio') }}">
                                    Explorar Veredas <i class="bi bi-compass ms-2"></i>
                                </a>
                                <button type="button" class="btn-projection" data-media-type="video" data-media-src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" data-media-title="Custodia del Territorio y Fuentes Hídricas">
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
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 2. Hero Principal con Logo Orbital --}}
<section class="hero">
    <div class="wrap hero-grid">
        <div>
            <div class="eyebrow">Cauca · Colombia · Comunidad afro</div>
            <h1>Un territorio que <em>se reconoce</em> y se proyecta.</h1>
            <p class="hero-lead">Una plataforma institucional para contar quiénes somos, cuidar la memoria colectiva y conectar las voces del Consejo Comunitario Zanjón de Garrapatero con su gente y con el mundo.</p>
            <div class="hero-actions">
                <a class="btn btn-main" href="{{ route('nosotros') }}">
                    Conocer el Consejo <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <a class="btn btn-ghost" href="{{ route('actualidad') }}">
                    Ver actualidad <i class="bi bi-newspaper ms-1"></i>
                </a>
            </div>
            <div class="hero-note">
                <div>
                    <strong>3.131</strong>
                    registros de referencia
                </div>
                <div class="rule"></div>
                <div>
                    <strong>08</strong>
                    territorios identificados
                </div>
            </div>
        </div>
        <div class="orbital">
            <div class="orbit-card float">
                <span class="orbit-dot d1"></span>
                <span class="orbit-dot d2"></span>
                <span class="orbit-dot d3"></span>
                <div class="orbit-logo">
                    <img src="{{ asset('logo/logo.webp') }}" alt="Identidad visual del Consejo Comunitario Zanjón de Garrapatero" width="220" height="220">
                </div>
                <div class="triad-mark" aria-label="Triada cromática institucional #172D4D, #F4F1EA y #B58A45">
                    <span class="triad-primary" title="#172D4D Principal"></span>
                    <span class="triad-secondary" title="#F4F1EA Secundario"></span>
                    <span class="triad-accent" title="#B58A45 Secundario"></span>
                </div>
                <div class="triad-key">
                    <b class="primary"></b> <span class="triad-val-primary">#172D4D</span> <b class="secondary"></b> <span class="triad-val-secondary">#F4F1EA</span> <b class="accent"></b> <span class="triad-val-accent">#B58A45</span>
                </div>
                <div class="orbit-caption">
                    <b>CCZG</b> · símbolo vivo del territorio
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 3. Resumen Nosotros --}}
<section class="section section-light">
    <div class="wrap">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="section-header mb-0">
                <div class="kicker">01 · Quiénes somos</div>
                <h2 class="section-title serif">Gobierno propio, memoria y futuro.</h2>
                <p class="section-copy">El Consejo Comunitario Zanjón de Garrapatero es una organización territorial afro que articula comunidad, cultura, participación y cuidado del territorio.</p>
            </div>
            <a href="{{ route('nosotros') }}" class="btn btn-main">
                Página Nosotros <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-7">
                <div class="p-4 p-lg-5 rounded-4 h-100" style="background: rgba(23, 45, 77, 0.05); border: 1px solid rgba(23, 45, 77, 0.1);">
                    <div class="kicker">Nuestra mirada</div>
                    <h3 class="serif display-6 mt-3">“La organización nace de la gente y vuelve a la gente.”</h3>
                    <p class="section-copy mt-3 mb-4">El Consejo Comunitario trabaja por el bienestar de las familias afrocolombianas, la protección de las cuencas hídricas y el ejercicio pacífico de la autonomía en el territorio.</p>
                    <a href="{{ route('nosotros') }}" class="read-more">Conocer visión, historia y gobernanza <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="glass-card p-4 p-lg-5 h-100" style="border: 1px solid rgba(181, 138, 69, 0.35);">
                    <div class="kicker">Identidad institucional</div>
                    <h3 class="mt-3" style="color: var(--brand-primary);">Símbolos y colores del Consejo</h3>
                    <p style="color: var(--text-muted); line-height: 1.7;">Cada tono representa los principios de la comunidad: soberanía territorial en azul #172D4D, dignidad y memoria en tono marfil #F4F1EA, y riqueza colectiva en oro ancestral #B58A45.</p>
                    <div class="stat-row">
                        <div class="stat">
                            <b style="color: var(--brand-primary);" class="triad-val-primary">#172D4D</b>
                            <span>Territorio</span>
                        </div>
                        <div class="stat">
                            <b style="color: var(--brand-accent);" class="triad-val-secondary">#F4F1EA</b>
                            <span>Dignidad</span>
                        </div>
                        <div class="stat">
                            <b style="color: var(--brand-accent);" class="triad-val-accent">#B58A45</b>
                            <span>Saber colectivo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 4. Resumen Territorio --}}
<section class="section">
    <div class="wrap">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="section-header mb-0">
                <div class="kicker">02 · Territorio</div>
                <h2 class="section-title serif">Un mapa de relaciones, no solo de lugares.</h2>
                <p class="section-copy">Veredas, reservas, centros comunitarios y espacios de encuentro que tejen la vida comunitaria.</p>
            </div>
            <a href="{{ route('territorio') }}" class="btn btn-ghost">
                Ver todos los territorios <i class="bi bi-map ms-1"></i>
            </a>
        </div>
        <div class="mini-grid">
            @if(isset($territories) && $territories->isNotEmpty())
                @foreach($territories->take(4) as $t)
                    <article class="territory">
                        @if($t->sector_badge)
                            <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(181, 138, 69, 0.3); color: #ffffff; border: 1px solid rgba(181, 138, 69, 0.5); font-size: 0.68rem; letter-spacing: 0.08em; text-transform: uppercase;">
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
                    <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(181, 138, 69, 0.3); color: #ffffff; border: 1px solid rgba(181, 138, 69, 0.5); font-size: 0.68rem; letter-spacing: 0.08em; text-transform: uppercase;">
                        <i class="bi bi-geo-alt-fill me-1"></i> Sector Central
                    </span>
                    <h3>El Palmar</h3>
                    <p>La mayor concentración de registros en la base de referencia comunitaria.</p>
                    <a href="{{ route('territorio') }}" class="read-more text-white">Conocer más <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="territory">
                    <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(181, 138, 69, 0.3); color: #ffffff; border: 1px solid rgba(181, 138, 69, 0.5); font-size: 0.68rem; letter-spacing: 0.08em; text-transform: uppercase;">
                        <i class="bi bi-tree-fill me-1"></i> Cuenca Alta
                    </span>
                    <h3>Santa Lucía</h3>
                    <p>Comunidad, recorridos y memoria que conectan el paisaje ancestral.</p>
                    <a href="{{ route('territorio') }}" class="read-more text-white">Conocer más <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="territory">
                    <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(181, 138, 69, 0.3); color: #ffffff; border: 1px solid rgba(181, 138, 69, 0.5); font-size: 0.68rem; letter-spacing: 0.08em; text-transform: uppercase;">
                        <i class="bi bi-water me-1"></i> Reserva Hídrica
                    </span>
                    <h3>La Toma</h3>
                    <p>Territorio y organización como ejercicio permanente de autonomía.</p>
                    <a href="{{ route('territorio') }}" class="read-more text-white">Conocer más <i class="bi bi-arrow-right"></i></a>
                </article>
                <article class="territory">
                    <span class="badge rounded-pill mb-2 px-2 py-1" style="background: rgba(181, 138, 69, 0.3); color: #ffffff; border: 1px solid rgba(181, 138, 69, 0.5); font-size: 0.68rem; letter-spacing: 0.08em; text-transform: uppercase;">
                        <i class="bi bi-people-fill me-1"></i> Familias Campesinas
                    </span>
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

{{-- 5. Resumen Actividades --}}
<section class="section manifesto">
    <div class="wrap">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="section-header mb-0">
                <div class="kicker">03 · Actividades</div>
                <h2 class="section-title serif">La organización se mueve en comunidad.</h2>
                <p class="section-copy">Acciones que sostienen el territorio y fortalecen la participación ciudadana.</p>
            </div>
            <a href="{{ route('actividades') }}" class="btn btn-main">
                Explorar actividades <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-3">
            <div class="col-md-6 col-xl-3">
                <article class="activity-card">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-4 p-3 mb-3" style="width: 54px; height: 54px; background: rgba(181, 138, 69, 0.16); color: var(--brand-accent); border: 1px solid rgba(181, 138, 69, 0.35);">
                        <i class="bi bi-megaphone fs-4"></i>
                    </div>
                    <h3>Asambleas</h3>
                    <p>Convocatorias, acuerdos y próximos encuentros de la comunidad.</p>
                    <a href="{{ route('actividades') }}" class="read-more mt-auto pt-2">Ver agenda <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="activity-card">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-4 p-3 mb-3" style="width: 54px; height: 54px; background: rgba(23, 45, 77, 0.08); color: var(--brand-primary); border: 1px solid rgba(23, 45, 77, 0.18);">
                        <i class="bi bi-tree fs-4"></i>
                    </div>
                    <h3>Cuidado territorial</h3>
                    <p>Acciones de protección, recorridos, reservas y memoria ambiental.</p>
                    <a href="{{ route('actividades') }}" class="read-more mt-auto pt-2">Ver acciones <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="activity-card">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-4 p-3 mb-3" style="width: 54px; height: 54px; background: rgba(181, 138, 69, 0.16); color: var(--brand-accent); border: 1px solid rgba(181, 138, 69, 0.35);">
                        <i class="bi bi-music-note-beamed fs-4"></i>
                    </div>
                    <h3>Cultura viva</h3>
                    <p>Cimarrona, saberes ancestrales y relatos que conectan generaciones.</p>
                    <a href="{{ route('actividades') }}" class="read-more mt-auto pt-2">Ver saberes <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
            <div class="col-md-6 col-xl-3">
                <article class="activity-card">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-4 p-3 mb-3" style="width: 54px; height: 54px; background: rgba(23, 45, 77, 0.08); color: var(--brand-primary); border: 1px solid rgba(23, 45, 77, 0.18);">
                        <i class="bi bi-person-check fs-4"></i>
                    </div>
                    <h3>Gestión comunitaria</h3>
                    <p>Censo, avales, proyectos y canales de atención para las familias.</p>
                    <a href="{{ route('actividades') }}" class="read-more mt-auto pt-2">Ver trámites <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
        </div>
    </div>
</section>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

{{-- 6. Resumen Galería --}}
<section class="section section-light">
    <div class="wrap">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="section-header mb-0">
                <div class="kicker">04 · Galería</div>
                <h2 class="section-title serif">Rostros, caminos y memoria.</h2>
                <p class="section-copy">Registro visual para documentar el territorio con fotografías reales de nuestra gente y sus paisajes.</p>
            </div>
            <a href="{{ route('galeria') }}" class="btn btn-main">
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

{{-- 7. Resumen Actualidad --}}
<section class="section section-light">
    <div class="wrap">
        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
            <div class="section-header mb-0">
                <div class="kicker">05 · Actualidad</div>
                <h2 class="section-title serif">La comunidad también se cuenta en presente.</h2>
            </div>
            <a href="{{ route('actualidad') }}" class="btn btn-main">
                Ir a Actualidad <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @if(isset($publications) && $publications->isNotEmpty())
                @foreach($publications->take(3) as $pub)
                    <div class="col-md-4">
                        <article class="news-card">
                            <span class="news-date">{{ $pub->category }} · {{ $pub->published_at ? $pub->published_at->format('d/m/Y') : date('Y') }}</span>
                            <h3>{{ $pub->title }}</h3>
                            <p>{{ $pub->summary ?: Str::limit($pub->content, 110) }}</p>
                            <a class="read-more" href="{{ route('actualidad') }}">Leer más <i class="bi bi-arrow-up-right"></i></a>
                        </article>
                    </div>
                @endforeach
            @else
                <div class="col-md-4">
                    <article class="news-card">
                        <span class="news-date">Agenda · {{ date('F Y') }}</span>
                        <h3>Presentación formal ante la asamblea</h3>
                        <p>Un encuentro para presentar la aplicación, sus objetivos y el camino de apropiación comunitaria.</p>
                        <a class="read-more" href="{{ route('actualidad') }}">Leer más <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="news-card">
                        <span class="news-date">Proceso · Censo</span>
                        <h3>Hacia un registro comunitario ampliado</h3>
                        <p>La plataforma permitirá solicitar registro, adjuntar documento y validar pertenencia territorial.</p>
                        <a class="read-more" href="{{ route('actualidad') }}">Conocer proceso <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
                <div class="col-md-4">
                    <article class="news-card">
                        <span class="news-date">Cultura · Memoria</span>
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

{{-- 8. Franja de Parallax con llamado a Contacto --}}
<section class="parallax-band" data-parallax="0.16">
    <div class="wrap">
        <div class="parallax-content">
            <div class="kicker">Territorio en movimiento</div>
            <h2 class="serif mt-3">Cada camino guarda una historia.</h2>
            <p class="mt-4">Participa activamente en las decisiones y en el crecimiento de nuestra comunidad afro.</p>
            <div class="d-flex gap-3 flex-wrap">
                <a class="btn btn-main" href="{{ route('contacto') }}">
                    Contáctanos <i class="bi bi-arrow-up-right ms-2"></i>
                </a>
                <a class="btn btn-ghost" href="{{ route('territorio') }}">
                    Explorar territorios <i class="bi bi-compass ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>
