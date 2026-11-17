@props([
    'name',
    'label',
    'required' => false,
    'icon' => null,
    'hint' => null,
])

<div class="mb-3">
    <label for="{{ $name }}" class="admin-label">
        <span>
            {{ $label }}
            @if($required)
                <span class="required-dot" title="Campo obligatorio">*</span>
            @endif
        </span>
        @if($hint)
            <span class="admin-label-hint">{{ $hint }}</span>
        @endif
    </label>

    <div class="admin-input-group">
        @if($icon)
            <i class="bi {{ $icon }} admin-input-icon"></i>
        @endif
        <select 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-select @error($name) is-invalid @enderror" 
            @if($required) required @endif
            {{ $attributes->except(['class']) }}
        >
            {{ $slot }}
        </select>
    </div>

    @error($name)
        <div class="text-danger small mt-1 fw-semibold">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror
</div>
