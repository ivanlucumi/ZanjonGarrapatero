<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'type', 'category', 'caption', 'file_url', 'thumbnail_url', 'duration', 'is_featured', 'is_visible', 'created_by'])]
class MediaItem extends Model
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
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Automatically create slug on creating.
     */
    protected static function booted(): void
    {
        static::creating(function (MediaItem $item): void {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->title).'-'.Str::random(6);
            }
        });
    }

    /**
     * Creator of the media item.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for visible media items.
     *
     * @param  Builder<MediaItem>  $query
     */
    public function scopeVisible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    /**
     * Scope for photos.
     *
     * @param  Builder<MediaItem>  $query
     */
    public function scopePhotos(Builder $query): void
    {
        $query->where('type', 'image');
    }

    /**
     * Scope for videos.
     *
     * @param  Builder<MediaItem>  $query
     */
    public function scopeVideos(Builder $query): void
    {
        $query->where('type', 'video');
    }

    /**
     * Determine if this media item is an image.
     */
    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    /**
     * Determine if this media item is a video.
     */
    public function isVideo(): bool
    {
        return $this->type === 'video';
    }

    /**
     * Get displayable thumbnail url (fallback to file_url or placeholder).
     */
    public function displayThumbnail(): string
    {
        if ($this->thumbnail_url) {
            return $this->thumbnail_url;
        }

        if ($this->isImage() && $this->file_url) {
            return $this->file_url;
        }

        return 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=600&q=80';
    }
}
