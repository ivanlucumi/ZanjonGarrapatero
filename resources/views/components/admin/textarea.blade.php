@props([
    'name',
    'label',
    'value' => '',
    'placeholder' => '',
    'rows' => 4,
    'required' => false,
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

    <textarea 
        name="{{ $name }}" 
        id="{{ $name }}" 
        rows="{{ $rows }}" 
        class="admin-textarea form-control @error($name) is-invalid @enderror" 
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        {{ $attributes->except(['class']) }}
    >{{ old($name, $value ?: (string)$slot) }}</textarea>

    @error($name)
        <div class="text-danger small mt-1 fw-semibold">
            <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
        </div>
    @enderror
</div>
