@extends('layouts.index')

@section('title', 'Galería | Consejo Comunitario Zanjón de Garrapatero')
@section('meta_description', 'Archivo fotográfico y memoria visual de la gente, paisajes, encuentros comunitarios y cultura afro en el Zanjón de Garrapatero.')

@section('content')
<div class="page-header">
    <div class="wrap">
        <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            <span>/</span>
            <span>Galería</span>
        </nav>
        <span class="glass-pill glass-pill-gold mb-2">04 · Archivo visual y fotográfico</span>
        <h1 class="page-header-title serif">Rostros, paisajes y memoria viva.</h1>
        <p class="page-header-subtitle">
            Una colección audiovisual para cuidar, visibilizar y celebrar la identidad de nuestra comunidad afrocolombiana y la riqueza de nuestras tierras.
        </p>
    </div>
</div>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

<section class="section">
    <div class="wrap">
        {{-- Carrusel Destacado de Galería --}}
        @php
            $carouselPhotos = (isset($images) && $images->isNotEmpty()) ? $images : collect([
                (object)['file_url' => 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1400&q=85', 'title' => 'Territorio que se cuida y protege', 'caption' => 'Montañas, cuencas hídricas y senderos ancestrales del Zanjón de Garrapatero'],
                (object)['file_url' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1400&q=85', 'title' => 'Encuentro y participación democrática', 'caption' => 'Familias y líderes reunidos en asamblea comunitaria'],
                (object)['file_url' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=1400&q=85', 'title' => 'Cultura viva y saberes ancestrales', 'caption' => 'Música afro, danza tradicional y memoria colectiva'],
            ]);
        @endphp

        <div id="galeriaFeaturedCarousel" class="carousel slide carousel-fade media-showcase-carousel mb-5" data-bs-ride="carousel" data-bs-interval="5500">
            <div class="carousel-indicators carousel-indicators-glass">
                @foreach($carouselPhotos as $idx => $photo)
                    <button type="button" data-bs-target="#galeriaFeaturedCarousel" data-bs-slide-to="{{ $idx }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Foto {{ $loop->iteration }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner rounded-4 overflow-hidden shadow-lg">
                @foreach($carouselPhotos as $photo)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="media-carousel-frame">
                            <img src="{{ $photo->file_url }}" alt="{{ $photo->title }}" class="w-100 h-100 object-fit-cover" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                            <div class="media-carousel-overlay"></div>
                            <div class="media-carousel-caption">
                                <span class="projection-badge mb-2"><span class="dot-live"></span> Fotografía Destacada</span>
                                <h2 class="serif text-white mb-1">{{ $photo->title }}</h2>
                                <p class="text-white-50 mb-3 small">{{ $photo->caption ?: $photo->title }}</p>
                                <button type="button" class="btn btn-glass btn-projection" data-media-type="image" data-media-src="{{ $photo->file_url }}" data-media-title="{{ $photo->title }}">
                                    <i class="bi bi-fullscreen me-1"></i> Ampliar fotografía
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-glass prev" type="button" data-bs-target="#galeriaFeaturedCarousel" data-bs-slide="prev" aria-label="Anterior fotografía">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button class="carousel-control-glass next" type="button" data-bs-target="#galeriaFeaturedCarousel" data-bs-slide="next" aria-label="Siguiente fotografía">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div class="d-flex gap-2 flex-wrap" role="group" aria-label="Filtro de fotografías">
                <span class="glass-pill glass-pill-gold">Todas las fotos</span>
                <span class="glass-pill">Territorio</span>
                <span class="glass-pill">Comunidad</span>
                <span class="glass-pill">Cultura</span>
            </div>
            <span class="small text-secondary"><i class="bi bi-info-circle me-1"></i>Haz clic sobre cualquier imagen para ampliarla en el visor</span>
        </div>

        <div class="gallery-grid mb-5">
            @if(isset($images) && $images->isNotEmpty())
                @foreach($images as $img)
                    <figure class="gallery-item">
                        <img src="{{ $img->file_url }}" alt="{{ $img->title }}" loading="lazy">
                        <figcaption>{{ $img->caption ?: $img->title }}</figcaption>
                    </figure>
                @endforeach
            @else
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=85" alt="Montañas y cuencas hídricas del Cauca" loading="lazy">
                    <figcaption>Territorio que se cuida y protege</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=800&q=85" alt="Familias y líderes en asamblea comunitaria" loading="lazy">
                    <figcaption>Encuentro y participación democrática</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=85" alt="Encuentro de música afro y danza tradicional" loading="lazy">
                    <figcaption>Cultura viva y saberes ancestrales</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1474511320723-9a56873867b5?auto=format&fit=crop&w=800&q=85" alt="Senderos ecológicos entre cafetales y árboles" loading="lazy">
                    <figcaption>Memoria del paisaje y caminos veredales</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=800&q=85" alt="Equipo del Consejo coordinando la gestión del censo" loading="lazy">
                    <figcaption>Organización, juventud y futuro</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=85" alt="Siembra de cultivos y cuidado ambiental en la cuenca" loading="lazy">
                    <figcaption>Minga de siembra y protección de fuentes de agua</figcaption>
                </figure>
                <figure class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1576267423445-b2e0074d68a4?auto=format&fit=crop&w=800&q=85" alt="Mayores compartiendo historias con niños del territorio" loading="lazy">
                    <figcaption>Transmisión oral y sabiduría de mayores</figcaption>
                </figure>
            @endif
        </div>

        {{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
        <div class="relief-divider" aria-hidden="true"></div>

        {{-- Convocatoria para aportar fotografías --}}
        <div class="glass-card p-4 p-lg-5 text-center">
            <div class="d-inline-flex p-3 rounded-circle mb-3" style="background: rgba(246, 189, 50, 0.15); color: var(--gold);">
                <i class="bi bi-camera fs-2"></i>
            </div>
            <h3 class="serif display-6 mb-2">¿Tienes fotografías históricas de tu vereda o familia?</h3>
            <p class="text-secondary mx-auto mb-4" style="max-width: 580px;">
                Ayúdanos a enriquecer el archivo visual comunitario. Aceptamos fotos históricas, registros de eventos familiares y paisajes de las 8 veredas.
            </p>
            <a href="{{ route('contacto') }}" class="btn btn-main">
                Aportar fotografía o video <i class="bi bi-cloud-arrow-up ms-1"></i>
            </a>
        </div>
    </div>
</section>
@endsection
