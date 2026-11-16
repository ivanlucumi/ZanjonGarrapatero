@extends('layouts.index')

@section('title', 'Territorio — Consejo Comunitario Zanjón de Garrapatero')
@section('meta_description', 'Conoce las veredas, cuencas hídricas, fincas y espacios comunitarios del Consejo Comunitario Zanjón de Garrapatero en el departamento del Cauca.')

@section('content')
<div class="page-header">
    <div class="wrap">
        <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            <span>/</span>
            <span>Territorio</span>
        </nav>
        <span class="glass-pill glass-pill-blue mb-2">02 · Espacio Ancestral &amp; Veredas</span>
        <h1 class="page-header-title serif">Un mapa de relaciones, cuencas y arraigo.</h1>
        <p class="page-header-subtitle">
            Nuestro territorio no es solo una delimitación geográfica: es el espacio vital donde perviven nuestras tradiciones, la memoria colectiva y la convivencia de las familias afrocolombianas.
        </p>
    </div>
</div>

<section class="section">
    <div class="wrap">
        {{-- Mapa y Datos Clave --}}
        <div class="row g-4 align-items-center mb-5">
            <div class="col-lg-7">
                <div class="glass-card p-4 p-lg-5">
                    <span class="kicker">Geografía Comunitaria</span>
                    <h2 class="serif mt-2 mb-3 display-6" style="color: var(--brand-primary);">Cauca · Colombia</h2>
                    <p class="section-copy">
                        El Consejo Comunitario Zanjón de Garrapatero se localiza en una zona de alta riqueza biológica y cultural. Sus tierras son fértiles para la agricultura tradicional campesina: café, plátano, frutales, cacao y pancoger.
                    </p>
                    <div class="row g-3 mt-3">
                        <div class="col-sm-6">
                            <div class="p-3 rounded-4" style="background: rgba(23, 45, 77, 0.05); border: 1px solid var(--line);">
                                <strong class="d-block fs-5" style="color: var(--brand-primary);">08 Territorios</strong>
                                <small style="color: var(--text-muted);">Zonas y veredas articuladas al censo</small>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="p-3 rounded-4" style="background: rgba(23, 45, 77, 0.05); border: 1px solid var(--line);">
                                <strong class="d-block fs-5" style="color: var(--brand-primary);">+3.100</strong>
                                <small style="color: var(--text-muted);">Registros en la base de referencia</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="glass-card p-4 p-lg-5 text-center">
                    <div class="d-inline-flex p-4 rounded-circle mb-3" style="background: rgba(181, 138, 69, 0.15); border: 2px dashed var(--brand-accent);">
                        <i class="bi bi-compass fs-1" style="color: var(--brand-accent);"></i>
                    </div>
                    <h3 class="h4 mb-2" style="color: var(--brand-primary);">Visor Cartográfico Comunitario</h3>
                    <p class="small mb-4" style="color: var(--text-muted);">
                        Estamos consolidando el sistema de información geográfica (SIG) para georreferenciar parcelas, humedales, centros educativos y caminos reales.
                    </p>
                    <a href="{{ route('contacto') }}" class="btn btn-ghost w-100 justify-content-center">
                        Solicitar Certificación Territorial <i class="bi bi-file-earmark-check ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Las 8 Veredas / Zonas Principales --}}
        <div class="section-header">
            <span class="kicker">Veredas y Asentamientos</span>
            <h2 class="section-title serif">Zonas que integran el Consejo</h2>
            <p class="section-copy">
                Cada territorio cuenta con sus comités locales, delegados ante la asamblea y saberes específicos.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <article class="territory">
                    <h3>El Palmar</h3>
                    <p>Corazón organizativo con la mayor densidad demográfica y actividades agropecuarias continuas.</p>
                    <a href="{{ route('contacto') }}">Detalle veredal <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="territory">
                    <h3>Santa Lucía</h3>
                    <p>Espacio de memoria viva, senderos ecológicos y tradición comunitaria de minga campesina.</p>
                    <a href="{{ route('contacto') }}">Detalle veredal <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="territory">
                    <h3>La Toma</h3>
                    <p>Enclave de autonomía histórica, resistencia cultural y salvaguarda de fuentes hídricas.</p>
                    <a href="{{ route('contacto') }}">Detalle veredal <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="territory">
                    <h3>Mazamorrero</h3>
                    <p>Cuna de saberes gastronómicos, música de tambores y cultivo solidario de pancoger.</p>
                    <a href="{{ route('contacto') }}">Detalle veredal <i class="bi bi-arrow-right"></i></a>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="glass-card p-4 h-100">
                    <span class="glass-pill glass-pill-gold mb-2">Vereda 05</span>
                    <h3 class="h4 mt-2" style="color: var(--brand-primary);">Ardovelas</h3>
                    <p class="small mb-0" style="color: var(--text-muted);">Comunidad campesina con alta producción agrícola y articulación activa en las asambleas del consejo.</p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="glass-card p-4 h-100">
                    <span class="glass-pill glass-pill-blue mb-2">Vereda 06</span>
                    <h3 class="h4 mt-2" style="color: var(--brand-primary);">Bajo San Francisco</h3>
                    <p class="small mb-0" style="color: var(--text-muted);">Riqueza hídrica, bosques protectores y proyectos de restauración ecológica participativa.</p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="glass-card p-4 h-100">
                    <span class="glass-pill glass-pill-gold mb-2">Vereda 07</span>
                    <h3 class="h4 mt-2" style="color: var(--brand-primary);">Los Ángeles</h3>
                    <p class="small mb-0" style="color: var(--text-muted);">Asentamiento tradicional de familias vinculadas a la preservación del patrimonio inmaterial.</p>
                </article>
            </div>
            <div class="col-md-6 col-lg-3">
                <article class="glass-card p-4 h-100">
                    <span class="glass-pill glass-pill-gold mb-2">Vereda 08</span>
                    <h3 class="h4 mt-2" style="color: var(--brand-primary);">Alto Palmar</h3>
                    <p class="small mb-0" style="color: var(--text-muted);">Mirador natural del valle interandino con fuerte presencia de la Guardia Cimarrona.</p>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
