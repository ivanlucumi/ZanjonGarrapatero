@extends('layouts.admin')

@section('title', 'Registrar Nueva Vereda / Territorio')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.territories.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver a Territorios
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Registrar Nueva Vereda o Sector</h2>
            <p class="text-muted small mb-0">Esta información se proyectará en la sección Territorio y en los filtros del censo.</p>
        </div>
    </div>

    <form action="{{ route('admin.territories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 1. INFORMACIÓN VEREDAL Y DEMOGRÁFICA --}}
        <x-admin.form-card title="Identificación y Datos Demográficos" icon="bi-geo-alt" kicker="01 · Localización">
            <div class="row g-3">
                <div class="col-md-8">
                    <x-admin.input name="name" label="Nombre de la Vereda o Asentamiento" icon="bi-signpost-2" placeholder="Ej. El Palmar, Santa Lucía, La Toma..." required />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="sector_badge" label="Sector / Cuenca" icon="bi-compass" placeholder="Ej. Sector Central, Cuenca Alta..." />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="estimated_families" type="number" label="Familias Estimadas" icon="bi-people" placeholder="Ej. 180" min="0" />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="watersheds" label="Afluentes / Cuencas Hídricas" icon="bi-water" placeholder="Ej. Río Zanjón, Quebrada Honda" />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="sort_order" type="number" label="Orden en el Mapa" icon="bi-sort-numeric-down" value="0" min="0" />
                </div>
                <div class="col-12">
                    <x-admin.input name="main_activities" label="Vocación Productiva y Ambiental" icon="bi-flower1" placeholder="Ej. Cacao bajo sombra, plátano, café, avicultura, custodia de humedales" />
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. PAISAJE Y CARACTERIZACIÓN --}}
        <x-admin.form-card title="Fotografía y Caracterización Cultural" icon="bi-image" kicker="02 · Paisaje & Memoria">
            <div class="row g-3">
                <div class="col-md-7">
                    <x-admin.dropzone name="image_file" label="Fotografía del Territorio" accept="image/*" badge="Optimización WebP automática • Máx 10 MB" />
                </div>
                <div class="col-md-5">
                    <x-admin.input name="image_url" label="O bien, enlace URL de imagen" icon="bi-link-45deg" placeholder="https://ejemplo.com/fotos/vereda.jpg" />
                </div>
                <div class="col-12">
                    <x-admin.textarea name="description" label="Descripción y Caracterización Territorial" placeholder="Historia de poblamiento, linderos ancestrales, sitios sagrados o de encuentro comunal..." rows="4" required />
                </div>
                <div class="col-12 mt-2">
                    <x-admin.switch name="is_visible" label="Publicar Inmediatamente en la Sección Territorio" description="Visible al público en el mapa y catálogo de veredas del Consejo Comunitario." :checked="true" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.territories.index') }}" submitText="Guardar Vereda" icon="bi-geo-alt-fill" />
    </form>
</div>
@endsection
