@extends('layouts.index')

@section('title', 'Videos | Consejo Comunitario Zanjón de Garrapatero')
@section('meta_description', 'Videoteca comunitaria y testimonios audiovisuales de los mayores, la Guardia Cimarrona y la cultura en el Consejo Comunitario Zanjón de Garrapatero.')

@section('content')
<div class="page-header">
    <div class="wrap">
        <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            <span>/</span>
            <span>Video</span>
        </nav>
        <span class="glass-pill glass-pill-gold mb-2">06 · Videoteca y testimonios</span>
        <h1 class="page-header-title serif">La voz del territorio en movimiento.</h1>
        <p class="page-header-subtitle">
            Un archivo en imágenes y sonido para que los relatos, cantos, asambleas y recorridos de nuestra comunidad queden grabados en la memoria colectiva.
        </p>
    </div>
</div>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

<section class="section">
    <div class="wrap">
        {{-- Carrusel Destacado de Videos --}}
        @php
            $carouselVideos = (isset($videos) && $videos->isNotEmpty()) ? $videos : collect([
                (object)[
                    'file_url' => asset('video/actividad-destacada.mp4'),
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1400&q=85',
                    'title' => 'Zanjón de Garrapatero: raíces, resistencia y autonomía',
                    'caption' => 'Registro audiovisual con mayoras y sabedores de El Palmar y Santa Lucía sobre la historia y defensa del territorio.',
                    'duration' => '12 min',
                ],
                (object)[
                    'file_url' => asset('video/guardia.mp4'),
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=1400&q=85',
                    'title' => 'Recorridos de la Guardia Cimarrona',
                    'caption' => 'Custodia comunitaria, linderos y ejercicio de soberanía pacífica en el territorio colectivo.',
                    'duration' => '8 min',
                ],
                (object)[
                    'file_url' => asset('video/cultura.mp4'),
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1400&q=85',
                    'title' => 'Cantos de Buga y Tambores Vivos',
                    'caption' => 'Ritmos tradicionales y saberes transmitidos por las cantoras del Zanjón de Garrapatero.',
                    'duration' => '6 min',
                ],
            ]);
        @endphp

        <div id="videosFeaturedCarousel" class="carousel slide carousel-fade media-showcase-carousel mb-5" data-bs-ride="carousel" data-bs-interval="6500">
            <div class="carousel-indicators carousel-indicators-glass">
                @foreach($carouselVideos as $idx => $vidItem)
                    <button type="button" data-bs-target="#videosFeaturedCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Video {{ $loop->iteration }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner rounded-4 overflow-hidden shadow-lg">
                @foreach($carouselVideos as $vidItem)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="media-carousel-frame">
                            <img src="{{ $vidItem->thumbnail_url ?: 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1400&q=85' }}" alt="{{ $vidItem->title }}" class="w-100 h-100 object-fit-cover" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                            <div class="media-carousel-overlay"></div>
                            <div class="media-carousel-caption">
                                <span class="projection-badge mb-2"><span class="dot-live"></span> Video Comunitario · {{ $vidItem->duration ?? 'HD' }}</span>
                                <h2 class="serif text-white mb-2">{{ $vidItem->title }}</h2>
                                <p class="text-white-50 mb-3" style="max-width: 620px;">{{ $vidItem->caption ?? 'Registro territorial audiovisual.' }}</p>
                                <button type="button" class="btn btn-main" data-video-src="{{ $vidItem->file_url }}" data-media-title="{{ $vidItem->title }}">
                                    <i class="bi bi-play-circle-fill me-2"></i> Reproducir Video
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-glass prev" type="button" data-bs-target="#videosFeaturedCarousel" data-bs-slide="prev" aria-label="Video anterior">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="carousel-control-glass next" type="button" data-bs-target="#videosFeaturedCarousel" data-bs-slide="next" aria-label="Siguiente video">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        {{-- Video Principal Destacado Luminoso --}}
        <div class="glass-card p-4 p-lg-5 mb-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-7">
                    <button type="button" class="gallery-item video-card w-100 border-0 p-0 rounded-4 overflow-hidden" data-video-src="{{ asset('video/actividad-destacada.mp4') }}" data-media-title="Documental: Zanjón de Garrapatero, Raíces y Territorio">
                        <img src="https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1400&q=85" alt="Paisaje natural del territorio para reproducción de video" loading="lazy">
                        <span class="play" aria-hidden="true"><i class="bi bi-play-fill"></i></span>
                        <span class="gallery-item figcaption">Reproducir video destacado del territorio</span>
                    </button>
                </div>
                <div class="col-lg-5">
                    <span class="glass-pill glass-pill-gold mb-3">Producción destacada</span>
                    <h2 class="serif h3 mb-3" style="color: var(--brand-primary);">Zanjón de Garrapatero: raíces, resistencia y autonomía</h2>
                    <p class="mb-4" style="color: var(--text-muted); line-height: 1.7;">
                        Este registro audiovisual compila entrevistas a mayoras y sabedores de El Palmar y Santa Lucía, relatando cómo se poblaron las riberas del zanjón y cómo se han defendido los linderos comunitarios a lo largo de las décadas.
                    </p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge border px-3 py-2" style="background: rgba(23, 45, 77, 0.06); border-color: var(--line) !important; color: var(--brand-primary);"><i class="bi bi-clock me-1" style="color: var(--brand-accent);"></i>Duración estimada: 12 min</span>
                        <span class="badge border px-3 py-2" style="background: rgba(23, 45, 77, 0.06); border-color: var(--line) !important; color: var(--brand-primary);"><i class="bi bi-camera-reels me-1" style="color: var(--brand-accent);"></i>HD 1080p</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
        <div class="relief-divider" aria-hidden="true"></div>

        {{-- Lista de Videos Comunitarios --}}
        <div class="section-header">
            <span class="kicker">Catálogo audiovisual</span>
            <h2 class="section-title serif">Registros de asambleas y tradición</h2>
        </div>

        <div class="row g-4">
            @if(isset($videos) && $videos->isNotEmpty())
                @foreach($videos as $vid)
                    <div class="col-md-6 col-lg-4">
                        <div class="glass-card p-3 h-100">
                            <button type="button" class="gallery-item video-card w-100 border-0 p-0 rounded-3 overflow-hidden mb-3" data-video-src="{{ $vid->file_url }}" data-media-title="{{ $vid->title }}">
                                <img src="{{ $vid->thumbnail_url ?: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=700&q=80' }}" alt="{{ $vid->title }}" loading="lazy" style="height: 200px; width: 100%; object-fit: cover;">
                                <span class="play" aria-hidden="true" style="font-size: 2rem;"><i class="bi bi-play-fill" style="width: 54px; height: 54px;"></i></span>
                            </button>
                            <h3 class="h5 mb-2" style="color: var(--brand-primary);">{{ $vid->title }}</h3>
                            <p class="small mb-0" style="color: var(--text-muted);">{{ $vid->caption ?? 'Registro territorial audiovisual.' }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card p-3 h-100">
                        <button type="button" class="gallery-item video-card w-100 border-0 p-0 rounded-3 overflow-hidden mb-3" data-video-src="{{ asset('video/guardia.mp4') }}" data-media-title="Recorrido de la Guardia Cimarrona">
                            <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=700&q=80" alt="Recorrido de la Guardia Cimarrona" loading="lazy" style="height: 200px;">
                            <span class="play" aria-hidden="true" style="font-size: 2rem;"><i class="bi bi-play-fill" style="width: 54px; height: 54px;"></i></span>
                        </button>
                        <h3 class="h5 mb-2" style="color: var(--brand-primary);">Recorridos de la Guardia Cimarrona</h3>
                        <p class="small mb-0" style="color: var(--text-muted);">Custodia comunitaria, linderos y ejercicio de soberanía pacífica en el territorio colectivo.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card p-3 h-100">
                        <button type="button" class="gallery-item video-card w-100 border-0 p-0 rounded-3 overflow-hidden mb-3" data-video-src="{{ asset('video/cultura.mp4') }}" data-media-title="Festival de Música Afro y Tambores">
                            <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=700&q=80" alt="Encuentro de música afro y danza tradicional" loading="lazy" style="height: 200px;">
                            <span class="play" aria-hidden="true" style="font-size: 2rem;"><i class="bi bi-play-fill" style="width: 54px; height: 54px;"></i></span>
                        </button>
                        <h3 class="h5 mb-2" style="color: var(--brand-primary);">Cantos de Buga y Tambores Vivos</h3>
                        <p class="small mb-0" style="color: var(--text-muted);">Ritmos tradicionales y saberes transmitidos por las cantoras del Zanjón de Garrapatero.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card p-3 h-100">
                        <button type="button" class="gallery-item video-card w-100 border-0 p-0 rounded-3 overflow-hidden mb-3" data-video-src="{{ asset('video/asamblea.mp4') }}" data-media-title="Asamblea General Ordinaria">
                            <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=700&q=80" alt="Asamblea general del Consejo Comunitario" loading="lazy" style="height: 200px;">
                            <span class="play" aria-hidden="true" style="font-size: 2rem;"><i class="bi bi-play-fill" style="width: 54px; height: 54px;"></i></span>
                        </button>
                        <h3 class="h5 mb-2" style="color: var(--brand-primary);">Acuerdos de Asamblea Comunitaria</h3>
                        <p class="small mb-0" style="color: var(--text-muted);">Resumen de las intervenciones y actas aprobadas por las familias participantes.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
