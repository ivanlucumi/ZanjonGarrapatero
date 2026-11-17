<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'category',
    'description',
    'file_path',
    'file_name',
    'file_size',
    'mime_type',
    'download_count',
    'is_visible',
    'created_by',
])]
class Document extends Model
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
            'file_size' => 'integer',
            'download_count' => 'integer',
        ];
    }

    /**
     * Boot model to automatically generate slug.
     */
    protected static function booted(): void
    {
        static::creating(function (Document $doc): void {
            if (empty($doc->slug)) {
                $baseSlug = Str::slug($doc->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }
                $doc->slug = $slug;
            }
        });
    }

    /**
     * Scope only visible documents.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    /**
     * Human-friendly formatted size (KB, MB).
     */
    public function formattedSize(): string
    {
        $bytes = (int) $this->file_size;

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1).' KB';
        }

        return $bytes.' bytes';
    }

    /**
     * Bootstrap icon corresponding to file type.
     */
    public function fileIcon(): string
    {
        $ext = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));

        return match ($ext) {
            'pdf' => 'bi-file-earmark-pdf text-danger',
            'doc', 'docx' => 'bi-file-earmark-word text-primary',
            'xls', 'xlsx', 'csv' => 'bi-file-earmark-excel text-success',
            'ppt', 'pptx' => 'bi-file-earmark-slides text-warning',
            'zip', 'rar', '7z', 'tar', 'gz' => 'bi-file-earmark-zip text-info',
            'jpg', 'jpeg', 'png', 'webp' => 'bi-file-earmark-image text-secondary',
            default => 'bi-file-earmark-text text-secondary',
        };
    }

    /**
     * Public storage URL for the document.
     */
    public function fileUrl(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * User who uploaded the document.
     *
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
