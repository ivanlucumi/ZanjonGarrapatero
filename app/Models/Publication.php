<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'category', 'summary', 'content', 'image_url', 'attachment_url', 'attachment_name', 'attachment_size', 'author_id', 'is_visible', 'published_at'])]
class Publication extends Model
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
            'published_at' => 'datetime',
            'attachment_size' => 'integer',
        ];
    }

    /**
     * Automatically create slug on creating.
     */
    protected static function booted(): void
    {
        static::creating(function (Publication $publication): void {
            if (empty($publication->slug)) {
                $publication->slug = Str::slug($publication->title).'-'.Str::random(6);
            }
            if (empty($publication->published_at)) {
                $publication->published_at = now();
            }
        });
    }

    /**
     * Author of the publication.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope for publicly visible publications.
     *
     * @param  Builder<Publication>  $query
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }
}
