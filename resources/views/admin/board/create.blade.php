@extends('layouts.admin')

@section('title', 'Registrar Autoridad o Directivo')

@section('content')
<div class="admin-form-container">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.board.index') }}" class="btn btn-sm btn-outline-secondary mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver a Autoridades
            </a>
            <h2 class="h3 fw-bold text-dark mb-1">Registrar Autoridad Tradicional o Directivo</h2>
            <p class="text-muted small mb-0">Esta información se presentará en la estructura organizativa de la página Nosotros.</p>
        </div>
    </div>

    <form action="{{ route('admin.board.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 1. IDENTIDAD Y ROL --}}
        <x-admin.form-card title="Identidad y Representación Étnica" icon="bi-person-badge" kicker="01 · Datos Principales">
            <div class="row g-3">
                <div class="col-md-6">
                    <x-admin.input name="name" label="Nombre y Apellidos" icon="bi-person" placeholder="Ej. Amalia Mina Lucumí" required />
                </div>
                <div class="col-md-6">
                    <x-admin.input name="role_title" label="Cargo o Designación Étnica" icon="bi-award" placeholder="Ej. Gobernador Mayor, Coordinadora de Sabedoras..." required />
                </div>
                <div class="col-md-6">
                    <x-admin.select name="committee" label="Comité o Instancia" icon="bi-diagram-3" required>
                        <option value="Junta de Gobierno" {{ old('committee') == 'Junta de Gobierno' ? 'selected' : '' }}>Junta de Gobierno</option>
                        <option value="Guardia Cimarrona" {{ old('committee') == 'Guardia Cimarrona' ? 'selected' : '' }}>Guardia Cimarrona</option>
                        <option value="Tierras y Ambiente" {{ old('committee') == 'Tierras y Ambiente' ? 'selected' : '' }}>Tierras y Ambiente</option>
                        <option value="Comité de Mujeres" {{ old('committee') == 'Comité de Mujeres' ? 'selected' : '' }}>Comité de Mujeres y Familia</option>
                        <option value="Comité de Cultura" {{ old('committee') == 'Comité de Cultura' ? 'selected' : '' }}>Comité de Cultura y Saberes</option>
                    </x-admin.select>
                </div>
                <div class="col-md-6">
                    <x-admin.input name="vereda" label="Vereda de Origen o Residencia" icon="bi-geo-alt" placeholder="Ej. El Palmar, Santa Lucía..." />
                </div>
            </div>
        </x-admin.form-card>

        {{-- 2. CONTACTO Y VISIBILIDAD --}}
        <x-admin.form-card title="Canales de Enlace y Parámetros" icon="bi-telephone" kicker="02 · Contacto & Orden">
            <div class="row g-3">
                <div class="col-md-5">
                    <x-admin.input name="email" type="email" label="Correo Electrónico" icon="bi-envelope" placeholder="autoridad@zanjon.gov.co" />
                </div>
                <div class="col-md-4">
                    <x-admin.input name="phone" label="Teléfono de Contacto" icon="bi-telephone" placeholder="Ej. 312 890 1234" />
                </div>
                <div class="col-md-3">
                    <x-admin.input name="sort_order" type="number" label="Orden en Organigrama" icon="bi-sort-numeric-down" value="0" />
                </div>
                <div class="col-12 mt-2">
                    <x-admin.switch name="is_visible" label="Visible en el Organigrama Público" description="Presentado inmediatamente en la página institucional 'Nosotros' para la comunidad." :checked="true" />
                </div>
            </div>
        </x-admin.form-card>

        {{-- 3. FOTOGRAFÍA Y BIOGRAFÍA --}}
        <x-admin.form-card title="Retrato y Trayectoria Comunitaria" icon="bi-image" kicker="03 · Imagen & Biografía">
            <div class="row g-3">
                <div class="col-md-7">
                    <x-admin.dropzone name="photo_file" label="Fotografía de la Autoridad" accept="image/*" badge="Optimización WebP cuadrada • Máx 10 MB" />
                </div>
                <div class="col-md-5">
                    <x-admin.input name="photo_url" label="O bien, enlace web externo" icon="bi-link-45deg" placeholder="https://ejemplo.com/fotos/directivo.jpg" />
                </div>
                <div class="col-12">
                    <x-admin.textarea name="bio" label="Reseña Biográfica y Liderazgo Comunitario" placeholder="Trayectoria organizativa, saberes ancestrales o papel desempeñado en la comunidad..." rows="3" />
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.board.index') }}" submitText="Guardar Autoridad" icon="bi-person-check" />
    </form>
</div>
@endsection
