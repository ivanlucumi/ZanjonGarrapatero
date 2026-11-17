<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'title',
    'subtitle',
    'badge_text',
    'type',
    'media_url',
    'poster_url',
    'projection_title',
    'primary_button_text',
    'primary_button_url',
    'secondary_button_text',
    'secondary_button_url',
    'sort_order',
    'is_visible',
    'created_by',
])]
class CarouselSlide extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * User who created the slide.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for publicly visible slides.
     *
     * @param  Builder<CarouselSlide>  $query
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    /**
     * Scope for ordering slides by sort_order.
     *
     * @param  Builder<CarouselSlide>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    /**
     * Determine if this slide features a video projection.
     */
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /**
     * Determine if this slide features an image projection.
     */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Determine if this slide is text-only (editorial / no media).
     */
    public function isTextOnly(): bool
    {
        return $this->type === 'none';
    }
}
