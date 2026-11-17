@props([
    'cancelUrl',
    'submitText' => 'Guardar Cambios',
    'cancelText' => 'Cancelar',
    'icon' => 'bi-check-circle',
])

<div class="admin-form-actions">
    <a href="{{ $cancelUrl }}" class="admin-btn-cancel">
        {{ $cancelText }}
    </a>
    <button type="submit" class="admin-btn-save">
        <i class="bi {{ $icon }} me-1"></i> {{ $submitText }}
    </button>
</div>
