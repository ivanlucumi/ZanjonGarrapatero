@props([
    'name',
    'label',
    'description' => null,
    'checked' => true,
    'value' => '1',
])

<div class="admin-switch-card">
    <div>
        <p class="switch-label">{{ $label }}</p>
        @if($description)
            <p class="switch-desc">{{ $description }}</p>
        @endif
    </div>
    <div class="form-check form-switch m-0">
        <input 
            class="form-check-input" 
            type="checkbox" 
            role="switch" 
            name="{{ $name }}" 
            id="{{ $name }}" 
            value="{{ $value }}" 
            {{ old($name, $checked ? '1' : '0') == '1' ? 'checked' : '' }}
            style="width: 2.8rem; height: 1.5rem; cursor: pointer;"
        >
    </div>
</div>
