@props([
    'title',
    'icon' => 'bi-pencil-square',
    'kicker' => null,
    'description' => null,
])

<div {{ $attributes->merge(['class' => 'admin-form-card']) }}>
    <div class="admin-form-card-header">
        <div class="admin-form-icon-pill">
            <i class="bi {{ $icon }}"></i>
        </div>
        <div>
            @if($kicker)
                <span class="admin-form-card-kicker">{{ $kicker }}</span>
            @endif
            <h3 class="admin-form-card-title">{{ $title }}</h3>
            @if($description)
                <p class="text-muted small mb-0">{{ $description }}</p>
            @endif
        </div>
    </div>
    <div class="admin-form-card-body">
        {{ $slot }}
    </div>
</div>
