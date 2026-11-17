<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'slug',
    'sector_badge',
    'description',
    'estimated_families',
    'watersheds',
    'main_activities',
    'image_url',
    'sort_order',
    'is_visible',
    'created_by',
])]
class Territory extends Model
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
            'estimated_families' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Automatically generate slug on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Territory $territory): void {
            if (empty($territory->slug)) {
                $territory->slug = Str::slug($territory->name);
            }
        });
    }

    /**
     * User who registered the territory.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for publicly visible territories.
     *
     * @param  Builder<Territory>  $query
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    /**
     * Scope for ordering territories by sort_order.
     *
     * @param  Builder<Territory>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }
}
