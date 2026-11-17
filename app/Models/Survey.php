<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'description', 'category', 'target_vereda', 'status', 'is_published', 'questions', 'created_by'])]
class Survey extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'questions' => 'array',
        ];
    }

    /**
     * Auto-generate slug on creation if empty.
     */
    protected static function booted(): void
    {
        static::creating(function (Survey $survey): void {
            if (empty($survey->slug)) {
                $survey->slug = Str::slug($survey->title).'-'.Str::random(6);
            }
        });
    }

    /**
     * Creator of the survey.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Responses submitted for this survey.
     *
     * @return HasMany<SurveyResponse, $this>
     */
    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class, 'survey_id');
    }

    /**
     * Scope for published surveys.
     *
     * @param  Builder<Survey>  $query
     */
    public function scopePublished(Builder $query): void
    {
        $query->where('is_published', true);
    }

    /**
     * Scope for active surveys.
     *
     * @param  Builder<Survey>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', 'active');
    }

    /**
     * Scope for vereda filtering (null means applies to all veredas).
     *
     * @param  Builder<Survey>  $query
     */
    public function scopeTargetedFor(Builder $query, ?string $vereda): void
    {
        if ($vereda === null || $vereda === '') {
            return;
        }

        $query->where(function (Builder $q) use ($vereda): void {
            $q->whereNull('target_vereda')
                ->orWhere('target_vereda', '')
                ->orWhere('target_vereda', $vereda);
        });
    }

    /**
     * Check if survey is actively accepting responses.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->is_published;
    }
}
