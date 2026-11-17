<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'email',
    'phone',
    'territory',
    'message',
    'status',
    'admin_notes',
    'attended_by',
    'attended_at',
    'ip_address',
    'user_agent',
])]
class ContactMessage extends Model
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
            'attended_at' => 'datetime',
        ];
    }

    /**
     * User who attended or replied to this message.
     *
     * @return BelongsTo<User, $this>
     */
    public function attendedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attended_by');
    }

    /**
     * Status badge metadata.
     *
     * @return array{class: string, label: string}
     */
    public function statusBadge(): array
    {
        return match ($this->status) {
            'pendiente' => ['class' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle', 'label' => 'Pendiente'],
            'en_tramite' => ['class' => 'bg-info-subtle text-info border-info-subtle', 'label' => 'En Trámite'],
            'atendido' => ['class' => 'bg-success-subtle text-success border-success-subtle', 'label' => 'Atendido'],
            'archivado' => ['class' => 'bg-secondary-subtle text-secondary border-secondary-subtle', 'label' => 'Archivado'],
            default => ['class' => 'bg-light text-dark border', 'label' => $this->status],
        };
    }
}
