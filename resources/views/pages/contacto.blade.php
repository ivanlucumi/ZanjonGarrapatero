@extends('layouts.index')

@section('title', 'Contacto & Censo — Consejo Comunitario Zanjón de Garrapatero')
@section('meta_description', 'Comunícate con la autoridad étnica del Consejo Comunitario Zanjón de Garrapatero o solicita registro en el censo comunitario afrocolombiano.')

@section('content')
<div class="page-header">
    <div class="wrap">
        <nav class="breadcrumbs" aria-label="Ruta de navegación">
            <a href="{{ route('index') }}"><i class="bi bi-house-door me-1"></i>Inicio</a>
            <span>/</span>
            <span>Contacto</span>
        </nav>
        <span class="glass-pill glass-pill-gold mb-2">07 · Atención Comunitaria &amp; Padrón</span>
        <h1 class="page-header-title serif">Hablemos del territorio.</h1>
        <p class="page-header-subtitle">
            Canales institucionales para trámites de avales, inscripción en el censo ampliado, comunicación con la Guardia Cimarrona y diálogo con la autoridad propia.
        </p>
    </div>
</div>

<section class="section">
    <div class="wrap">
        @if (session('status_success'))
            <div class="alert alert-glass-success mb-4 d-flex align-items-center gap-3" role="alert">
                <i class="bi bi-check-circle-fill fs-3" style="color: var(--brand-accent);"></i>
                <div>
                    <strong class="d-block" style="color: var(--brand-primary);">¡Mensaje enviado con éxito!</strong>
                    <span style="color: var(--text-muted);">{{ session('status_success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger rounded-4 p-4 mb-4" role="alert" style="background: rgba(220, 38, 38, 0.08); border: 1px solid rgba(220, 38, 38, 0.3); color: #991b1b;">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                    <strong>Por favor corrige los siguientes errores:</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-5 align-items-start">
            {{-- Columna de Información Institucional --}}
            <div class="col-lg-5">
                <div class="glass-card p-4 p-lg-5 mb-4">
                    <span class="kicker">Correos Institucionales</span>
                    <h2 class="serif h4 mt-2 mb-4" style="color: var(--brand-primary);">Canales directos de atención</h2>

                    <div class="contact-list">
                        <a href="mailto:comunicaciones@consejozanjondegarrapatero.org">
                            <i class="bi bi-megaphone"></i>
                            <div>
                                <span class="d-block small" style="color: var(--text-muted);">Comunicaciones</span>
                                <strong style="color: var(--brand-primary);">comunicaciones@consejozanjondegarrapatero.org</strong>
                            </div>
                        </a>
                        <a href="mailto:guardiacimarrona@consejozanjondegarrapatero.org">
                            <i class="bi bi-shield-check"></i>
                            <div>
                                <span class="d-block small" style="color: var(--text-muted);">Guardia Cimarrona</span>
                                <strong style="color: var(--brand-primary);">guardiacimarrona@consejozanjondegarrapatero.org</strong>
                            </div>
                        </a>
                        <a href="mailto:autoridadzanjon@consejozanjondegarrapatero.org">
                            <i class="bi bi-envelope"></i>
                            <div>
                                <span class="d-block small" style="color: var(--text-muted);">Autoridad Propia</span>
                                <strong style="color: var(--brand-primary);">autoridadzanjon@consejozanjondegarrapatero.org</strong>
                            </div>
                        </a>
                        <a href="mailto:cczg@consejozanjondegarrapatero.org">
                            <i class="bi bi-chat-square-heart"></i>
                            <div>
                                <span class="d-block small" style="color: var(--text-muted);">Coordinación General</span>
                                <strong style="color: var(--brand-primary);">cczg@consejozanjondegarrapatero.org</strong>
                            </div>
                        </a>
                    </div>

                    <div class="mt-4 pt-4 border-top" style="border-color: var(--line) !important;">
                        <small class="d-block mb-1" style="color: var(--text-muted);">Dominio Institucional Oficial:</small>
                        <strong class="fs-6" style="color: var(--brand-primary);">cczanjongarrapatero.org</strong>
                        <p class="small mt-2 mb-0" style="color: var(--text-muted);">Departamento del Cauca, República de Colombia</p>
                    </div>
                </div>

                {{-- Horarios y Lugar de Asambleas --}}
                <div class="glass-card p-4">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-geo-alt fs-2" style="color: var(--brand-accent);"></i>
                        <div>
                            <strong class="d-block" style="color: var(--brand-primary);">Sede y Salón Comunitario</strong>
                            <small style="color: var(--text-muted);">Vereda El Palmar · Encuentros según asamblea convocada</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulario Seguro de Contacto / Registro de Interés --}}
            <div class="col-lg-7">
                <div class="contact-panel">
                    <span class="glass-pill glass-pill-gold mb-3">Registro de Interés &amp; Censo</span>
                    <h2 class="serif display-6 mb-2" style="color: var(--brand-primary);">Escríbenos o solicita información</h2>
                    <p class="mb-4" style="color: var(--text-muted);">
                        Diligencia este formulario para trámites de certificación de censo, solicitudes comunitarias o contacto con los directivos.
                    </p>

                    <form action="{{ route('contacto.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6 form-glass-group">
                                <label for="name" class="form-glass-label">Nombre Completo <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-glass-input @error('name') border-danger @enderror" placeholder="Tu nombre y apellidos" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="form-glass-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-glass-group">
                                <label for="email" class="form-glass-label">Correo Electrónico <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-glass-input @error('email') border-danger @enderror" placeholder="ejemplo@correo.com" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="form-glass-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-glass-group">
                                <label for="phone" class="form-glass-label">Teléfono o WhatsApp</label>
                                <input type="tel" name="phone" id="phone" class="form-glass-input @error('phone') border-danger @enderror" placeholder="Ej. 312 345 6789" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="form-glass-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 form-glass-group">
                                <label for="territory" class="form-glass-label">Vereda o Territorio <span class="text-danger">*</span></label>
                                <select name="territory" id="territory" class="form-glass-select @error('territory') border-danger @enderror" required>
                                    <option value="" disabled {{ old('territory') ? '' : 'selected' }}>Selecciona tu territorio</option>
                                    <option value="El Palmar" {{ old('territory') == 'El Palmar' ? 'selected' : '' }}>El Palmar</option>
                                    <option value="Santa Lucía" {{ old('territory') == 'Santa Lucía' ? 'selected' : '' }}>Santa Lucía</option>
                                    <option value="La Toma" {{ old('territory') == 'La Toma' ? 'selected' : '' }}>La Toma</option>
                                    <option value="Mazamorrero" {{ old('territory') == 'Mazamorrero' ? 'selected' : '' }}>Mazamorrero</option>
                                    <option value="Ardovelas" {{ old('territory') == 'Ardovelas' ? 'selected' : '' }}>Ardovelas</option>
                                    <option value="Bajo San Francisco" {{ old('territory') == 'Bajo San Francisco' ? 'selected' : '' }}>Bajo San Francisco</option>
                                    <option value="Los Ángeles" {{ old('territory') == 'Los Ángeles' ? 'selected' : '' }}>Los Ángeles</option>
                                    <option value="Alto Palmar" {{ old('territory') == 'Alto Palmar' ? 'selected' : '' }}>Alto Palmar</option>
                                    <option value="Otro territorio o externo" {{ old('territory') == 'Otro territorio o externo' ? 'selected' : '' }}>Otro territorio / Ciudad</option>
                                </select>
                                @error('territory')
                                    <div class="form-glass-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 form-glass-group">
                                <label for="message" class="form-glass-label">Motivo o Mensaje <span class="text-danger">*</span></label>
                                <textarea name="message" id="message" rows="5" class="form-glass-textarea @error('message') border-danger @enderror" placeholder="Describe tu solicitud, inquietud sobre el censo o proyecto comunitario..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="form-glass-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-4 small" style="color: var(--text-muted);">
                            <i class="bi bi-shield-lock-fill" style="color: var(--brand-accent);"></i>
                            <span>Tus datos se manejan de conformidad con la Ley de Tratamiento de Datos Personales (Habeas Data) y para fines exclusivamente comunitarios.</span>
                        </div>

                        <button type="submit" class="btn btn-main py-3 px-4 w-100 justify-content-center fs-6">
                            Enviar Solicitud al Consejo <i class="bi bi-send-fill ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
