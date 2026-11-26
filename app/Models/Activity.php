<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable(['title', 'slug', 'category', 'description', 'location', 'event_date', 'active_from', 'active_until', 'status', 'created_by', 'is_visible'])]
class Activity extends Model
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
            'event_date' => 'datetime',
            'active_from' => 'datetime',
            'active_until' => 'datetime',
        ];
    }

    /**
     * Automatically create slug on creating.
     */
    protected static function booted(): void
    {
        static::creating(function (Activity $activity): void {
            if (empty($activity->slug)) {
                $activity->slug = Str::slug($activity->title).'-'.Str::random(6);
            }
        });
    }

    /**
     * Creator of the activity.
     *
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for visible activities within active date window.
     *
     * @param  Builder<Activity>  $query
     */
    public function scopeVisible(Builder $query): void
    {
        $now = now();

        $query->where('is_visible', true)
            ->where(function (Builder $q) use ($now): void {
                $q->whereNull('active_from')->orWhere('active_from', '<=', $now);
            })
            ->where(function (Builder $q) use ($now): void {
                $q->whereNull('active_until')->orWhere('active_until', '>=', $now);
            });
    }
}
