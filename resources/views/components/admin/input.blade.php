@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
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
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            class="form-control @error($name) is-invalid @enderror" 
            value="{{ old($name, $value) }}" 
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            {{ $attributes->except(['class']) }}
        >
    </div>

    @error($name)
        <div class="text-danger small mt-1 fw-semibold">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror
</div>
