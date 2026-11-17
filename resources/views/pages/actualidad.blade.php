@extends('layouts.index')

@section('title', 'Actualidad | Consejo Comunitario Zanjón de Garrapatero')
@section('meta_description', 'Noticias, comunicados oficiales, avances del censo poblacional y proyectos del Consejo Comunitario Zanjón de Garrapatero.')

@section('content')
<div class="page-header">
    <div class="wrap">
        <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            <span>/</span>
            <span>Actualidad</span>
        </nav>
        <span class="glass-pill glass-pill-blue mb-2">05 · Comunicados y avances</span>
        <h1 class="page-header-title serif">Noticias, acuerdos y vida en el territorio.</h1>
        <p class="page-header-subtitle">
            Un archivo público y transparente para que cada habitante de las veredas se mantenga informado con información verificada y de primera mano.
        </p>
    </div>
</div>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

<section class="section">
    <div class="wrap">
        {{-- Noticia Destacada Luminosa --}}
        <div class="glass-card p-4 p-lg-5 mb-5" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(181, 138, 69, 0.12)); border: 1px solid rgba(181, 138, 69, 0.35);">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <span class="glass-pill glass-pill-gold mb-3">Convocatoria principal</span>
                    <h2 class="serif display-6 mb-3" style="color: var(--brand-primary);">Presentación de la plataforma digital y socialización del censo étnico</h2>
                    <p class="mb-4" style="color: var(--text-muted); line-height: 1.75;">
                        Se invita a todos los delegados veredales, comuneros, sabedores y jóvenes a la asamblea comunitaria extraordinaria donde se presentará la arquitectura tecnológica de información del Consejo Comunitario, diseñada para resguardar nuestros datos con soberanía.
                    </p>
                    <div class="d-flex gap-3 flex-wrap align-items-center">
                        <span class="small" style="color: var(--brand-accent);"><i class="bi bi-calendar3 me-1"></i>Diciembre {{ date('Y') }}</span>
                        <span class="small" style="color: var(--brand-primary);"><i class="bi bi-geo-alt me-1 text-danger"></i>Salón El Palmar</span>
                        <a href="{{ route('contacto') }}" class="btn btn-main ms-lg-3">Confirmar asistencia <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="p-4 rounded-4" style="background: rgba(23, 45, 77, 0.05); border: 1px solid var(--line);">
                        <i class="bi bi-newspaper display-3" style="color: var(--brand-accent);"></i>
                        <h3 class="h6 mt-3 mb-1" style="color: var(--brand-primary);">Boletín oficial No. 04</h3>
                        <small class="d-block" style="color: var(--text-muted);">Documento para descarga en PDF próximamente</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
        <div class="relief-divider" aria-hidden="true"></div>

        {{-- Cuadrícula de Noticias y Artículos --}}
        <div class="row g-4 mb-5">
            @if(isset($publications) && $publications->isNotEmpty())
                @foreach($publications as $pub)
                    <div class="col-md-6 col-lg-4">
                        <article class="news-card h-100 d-flex flex-column">
                            @if($pub->image_url)
                                <div class="mb-3 rounded-4 overflow-hidden" style="height: 180px;">
                                    <img src="{{ $pub->image_url }}" alt="{{ $pub->title }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                                </div>
                            @endif
                            <span class="news-date">{{ $pub->category }} · {{ $pub->published_at ? $pub->published_at->format('d/m/Y') : date('Y') }}</span>
                            <h3>{{ $pub->title }}</h3>
                            <p class="flex-grow-1">{{ $pub->summary ?: \Illuminate\Support\Str::limit($pub->content, 120) }}</p>
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pt-2 border-top mt-2" style="border-color: var(--line) !important;">
                                <a class="read-more" href="{{ route('contacto') }}">Más información <i class="bi bi-arrow-up-right"></i></a>
                                @if($pub->attachment_url)
                                    <a class="btn btn-sm btn-outline-primary py-1 px-2" href="{{ $pub->attachment_url }}" target="_blank" download title="{{ $pub->attachment_name ?? 'Descargar documento anexo' }}" style="font-size: 0.8rem;">
                                        <i class="bi bi-paperclip me-1"></i> Anexo PDF
                                    </a>
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach

                @if($publications->hasPages())
                    <div class="col-12 mt-4 d-flex justify-content-center">
                        {{ $publications->links() }}
                    </div>
                @endif
            @else
                <div class="col-md-6 col-lg-4">
                    <article class="news-card">
                        <span class="news-date">Censo Territorial · {{ date('Y') }}</span>
                        <h3>Padrón comunitario: pasos para registrar a tu núcleo familiar</h3>
                        <p>Conoce los documentos y el formulario digital que facilitará la inscripción sin intermediarios ni costos.</p>
                        <a class="read-more" href="{{ route('contacto') }}">Ver guía paso a paso <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4">
                    <article class="news-card">
                        <span class="news-date">Medio Ambiente · Aguas</span>
                        <h3>Jornada de limpieza y protección de microcuencas</h3>
                        <p>La comisión ambiental junto con la Guardia Cimarrona lideraron la reforestación de la ribera del zanjón.</p>
                        <a class="read-more" href="{{ route('actividades') }}">Ver balance de la jornada <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4">
                    <article class="news-card">
                        <span class="news-date">Cultura · Memoria</span>
                        <h3>Taller de peinados y trenzados tradicionales con sabedoras</h3>
                        <p>Un espacio de encuentro intergeneracional para reivindicar los mapas de libertad grabados en el cabello afro.</p>
                        <a class="read-more" href="{{ route('galeria') }}">Ver fotografías del taller <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4">
                    <article class="news-card">
                        <span class="news-date">Gobernanza · Legal</span>
                        <h3>Actualización del reglamento interno del Consejo Comunitario</h3>
                        <p>Avances en las mesas de concertación con los comités de cada una de las ocho veredas participantes.</p>
                        <a class="read-more" href="{{ route('nosotros') }}">Conocer estructura <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4">
                    <article class="news-card">
                        <span class="news-date">Juventud · Formación</span>
                        <h3>Escuela de líderes jóvenes y derechos étnicos colectivos</h3>
                        <p>Formación continua en la Ley 70 de 1993, formulación de proyectos productivos y oratoria comunitaria.</p>
                        <a class="read-more" href="{{ route('actividades') }}">Próximas inscripciones <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4">
                    <article class="news-card">
                        <span class="news-date">Soberanía Alimentaria</span>
                        <h3>Feria comunitaria de intercambio de semillas nativas</h3>
                        <p>Productores de Santa Lucía, El Palmar y Mazamorrero intercambiaron variedades tradicionales de maíz y frijol.</p>
                        <a class="read-more" href="{{ route('contacto') }}">Saber más <i class="bi bi-arrow-up-right"></i></a>
                    </article>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
