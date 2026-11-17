@extends('layouts.index')

@section('title', 'Documentos y Repositorio Institucional | Consejo Comunitario Zanjón de Garrapatero')
@section('meta_description', 'Consulta y descarga acuerdos de asamblea, estatutos de gobierno propio, cartografía, censos y planes ambientales del Consejo Comunitario Zanjón de Garrapatero.')

@section('content')
<div class="page-header">
    <div class="wrap">
        <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            <span>/</span>
            <span>Documentos</span>
        </nav>
        <span class="glass-pill glass-pill-gold mb-2">08 · Transparencia y Memoria Documental</span>
        <h1 class="page-header-title serif">Repositorio Institucional y Normatividad Étnica</h1>
        <p class="page-header-subtitle">
            Acceso libre y transparente a las actas de asamblea, estatutos internos, acuerdos de convivencia, cartografía social y planes de manejo ambiental territorial.
        </p>
    </div>
</div>

{{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
<div class="relief-divider" aria-hidden="true"></div>

<section class="section">
    <div class="wrap">
        <!-- BUSCADOR Y FILTROS POR CATEGORÍA -->
        <div class="glass-card p-4 mb-5">
            <form method="GET" action="{{ route('documentos') }}" class="row g-3 align-items-center">
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0" style="color: var(--brand-accent);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por título, temática o palabra clave..." value="{{ $currentSearch }}" style="background: rgba(255,255,255,0.7);">
                    </div>
                </div>
                <div class="col-lg-4">
                    <select name="category" class="form-select" style="background: rgba(255,255,255,0.7);">
                        <option value="">Todas las categorías institucionales</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $currentCategory === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-main w-100 justify-content-center">Buscar</button>
                    @if($currentSearch || $currentCategory)
                        <a href="{{ route('documentos') }}" class="btn btn-ghost px-3" title="Limpiar filtros"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <!-- LISTADO DE DOCUMENTOS -->
        @if(isset($documents) && $documents->isNotEmpty())
            <div class="row g-4 mb-5">
                @foreach($documents as $doc)
                    <div class="col-md-6 col-lg-4">
                        <article class="glass-card p-4 h-100 d-flex flex-column justify-content-between position-relative">
                            <div>
                                <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                                    <div class="rounded-4 p-3 d-flex align-items-center justify-content-center" style="background: rgba(23, 45, 77, 0.08); border: 1px solid var(--line); font-size: 1.8rem;">
                                        <i class="bi {{ $doc->fileIcon() }}"></i>
                                    </div>
                                    <span class="glass-pill glass-pill-gold small">{{ $doc->category }}</span>
                                </div>
                                <h2 class="h5 fw-bold mb-2" style="color: var(--brand-primary); line-height: 1.4;">
                                    {{ $doc->title }}
                                </h2>
                                @if($doc->description)
                                    <p class="small text-muted mb-3" style="line-height: 1.6;">
                                        {{ \Illuminate\Support\Str::limit($doc->description, 130) }}
                                    </p>
                                @endif
                            </div>

                            <div class="pt-3 border-top mt-3" style="border-color: var(--line) !important;">
                                <div class="d-flex align-items-center justify-content-between small text-muted mb-3">
                                    <span><i class="bi bi-file-earmark me-1"></i>{{ $doc->formattedSize() }}</span>
                                    <span><i class="bi bi-download me-1"></i>{{ $doc->download_count }} descargas</span>
                                </div>
                                <a href="{{ route('documentos.download', $doc->slug) }}" class="btn btn-main w-100 justify-content-center">
                                    <i class="bi bi-cloud-arrow-down me-2"></i> Descargar Documento
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            @if(method_exists($documents, 'hasPages') && $documents->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $documents->links() }}
                </div>
            @endif
        @else
            <div class="glass-card p-5 text-center my-4">
                <i class="bi bi-folder2-open display-4 text-muted mb-3 d-block"></i>
                <h3 class="serif h4 mb-2" style="color: var(--brand-primary);">No se encontraron documentos disponibles</h3>
                <p class="text-muted mx-auto" style="max-width: 540px;">
                    @if($currentSearch || $currentCategory)
                        No hay coincidencias con los criterios de búsqueda aplicados. Intenta con otros términos o limpia los filtros.
                    @else
                        Los acuerdos y actas institucionales están siendo digitalizados por la secretaría del Consejo y estarán disponibles próximamente.
                    @endif
                </p>
                @if($currentSearch || $currentCategory)
                    <a href="{{ route('documentos') }}" class="btn btn-main mt-3">Ver todos los documentos</a>
                @endif
            </div>
        @endif

        {{-- Separador con Efecto de Relieve y Dota / Gota Central --}}
        <div class="relief-divider" aria-hidden="true"></div>

        <!-- BANNER DE CONTACTO PARA SOLICITUD DE DOCUMENTOS -->
        <div class="glass-card p-4 p-lg-5 mt-5 text-center" style="background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(181, 138, 69, 0.15)); border: 1px solid rgba(181, 138, 69, 0.35);">
            <h3 class="serif display-6 mb-3" style="color: var(--brand-primary);">¿Requieres un certificado territorial o documento no listado?</h3>
            <p class="mx-auto" style="max-width: 620px; color: var(--text-muted);">
                Puedes radicar tu solicitud directamente a través de nuestro canal de atención ciudadana y trámites de censo.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
                <a href="{{ route('contacto') }}" class="btn btn-main">Radicar Solicitud <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</section>
@endsection
