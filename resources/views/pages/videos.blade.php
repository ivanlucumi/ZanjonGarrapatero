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
