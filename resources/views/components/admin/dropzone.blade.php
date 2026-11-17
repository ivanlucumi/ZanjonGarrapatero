@props([
    'name',
    'label',
    'accept' => '*/*',
    'badge' => 'Máx 10 MB',
    'current' => null,
    'currentUrl' => null,
    'required' => false,
])

<div class="mb-3">
    <label class="admin-label">
        <span>
            {{ $label }}
            @if($required)
                <span class="required-dot" title="Campo obligatorio">*</span>
            @endif
        </span>
        @if($current)
            <span class="admin-label-hint text-success">
                <i class="bi bi-check2-circle me-1"></i>Archivo actual cargado
            </span>
        @endif
    </label>

    <div class="admin-dropzone-box">
        <input 
            type="file" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="admin-dropzone-input" 
            accept="{{ $accept }}"
            @if($required) required @endif
            {{ $attributes->except(['class']) }}
        >
        <div class="dz-icon">
            <i class="bi bi-cloud-arrow-up"></i>
        </div>
        <div class="dz-title">
            <span>Haz clic para explorar</span> o arrastra el archivo aquí
        </div>
        <span class="dz-badge">{{ $badge }}</span>

        @if($currentUrl)
            <div class="mt-2 small text-muted">
                Archivo actual: <a href="{{ $currentUrl }}" target="_blank" class="fw-semibold text-primary text-decoration-underline" style="pointer-events: auto; position: relative; z-index: 20;">Ver recurso</a>
            </div>
        @endif

        <div class="admin-dropzone-preview">
            <i class="bi bi-check-circle-fill text-success"></i>
            <span class="dz-filename">Archivo seleccionado</span>
        </div>
    </div>

    @error($name)
        <div class="text-danger small mt-1 fw-semibold">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror
</div>
