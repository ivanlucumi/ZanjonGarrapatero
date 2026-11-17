@extends('layouts.admin')

@section('title', 'Editar Vereda / Territorio')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.territories.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver a Territorios
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Editar: {{ $territory->name }}</h2>
            <p class="text-muted small mb-0">Toda modificación quedará registrada con fecha, usuario e IP en la bitácora de auditoría.</p>
        </div>
        @if($territory->trashed())
            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill">
                <i class="bi bi-archive-fill me-1"></i> En papelera / Histórico (Inactivo)
            </span>
        @endif
    </div>

    <form action="{{ route('admin.territories.update', $territory->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. INFORMACIÓN VEREDAL Y DEMOGRÁFICA --}}
        <x-admin.form-card title="Identificación y Datos Demográficos" icon="bi-geo-alt" kicker="01 · Localización">
            <div class="row g-3">
                <div class="col-md-8">
                    <x-admin.input name="name" label="Nombre de la Vereda o Asentamiento" icon="bi-signpost-2" :value="$territory->name" required />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="sector_badge" label="Sector / Cuenca" icon="bi-compass" :value="$territory->sector_badge" />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="estimated_families" type="number" label="Familias Estimadas" icon="bi-people" :value="$territory->estimated_families" min="0" />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="watersheds" label="Afluentes / Cuencas Hídricas" icon="bi-water" :value="$territory->watersheds" />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="sort_order" type="number" label="Orden en el Mapa" icon="bi-sort-numeric-down" :value="$territory->sort_order" min="0" />
                </div>
                <div class="col-12">
                    <x-admin.input name="main_activities" label="Vocación Productiva y Ambiental" icon="bi-flower1" :value="$territory->main_activities" />
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. PAISAJE Y CARACTERIZACIÓN --}}
        <x-admin.form-card title="Fotografía y Caracterización Cultural" icon="bi-image" kicker="02 · Paisaje & Memoria">
            <div class="row g-3">
                <div class="col-md-7">
                    <x-admin.dropzone name="image_file" label="Reemplazar Fotografía (Opcional)" accept="image/*" badge="Optimización WebP automática • Máx 10 MB" />
                </div>
                <div class="col-md-5">
                    <x-admin.input name="image_url" label="O bien, URL de imagen" icon="bi-link-45deg" :value="$territory->image_url" />
                    @if($territory->image_url)
                        <div class="small text-muted mt-1 text-truncate">
                            <span class="fw-semibold">Foto actual:</span> <code>{{ $territory->image_url }}</code>
                        </div>
                    @endif
                </div>
                <div class="col-12">
                    <x-admin.textarea name="description" label="Descripción y Caracterización Territorial" rows="4" required>{{ old('description', $territory->description) }}</x-admin.textarea>
                </div>
                <div class="col-12 mt-2">
                    <x-admin.switch name="is_visible" label="Publicar en la Sección Territorio" description="Visible al público en el mapa y catálogo de veredas del Consejo Comunitario." :checked="$territory->is_visible" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.territories.index') }}" submitText="Guardar Cambios" icon="bi-check-lg" />
    </form>
</div>
@endsection
