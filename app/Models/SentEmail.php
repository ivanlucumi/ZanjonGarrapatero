<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['sender_id', 'send_type', 'target_role', 'recipient_user_id', 'subject', 'body', 'recipients_count', 'failed_count', 'failed_recipients', 'recipient_emails', 'attachments_meta', 'sent_at'])]
class SentEmail extends Model
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
            'recipient_emails' => 'array',
            'failed_recipients' => 'array',
            'attachments_meta' => 'array',
            'recipients_count' => 'integer',
            'failed_count' => 'integer',
            'sent_at' => 'datetime',
        ];
    }

    /**
     * Sender user.
     *
     * @return BelongsTo<User, $this>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Target user for individual emails.
     *
     * @return BelongsTo<User, $this>
     */
    public function recipientUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    /**
     * Human-readable label for send_type.
     */
    public function typeLabel(): string
    {
        return match ($this->send_type) {
            'massive' => 'Masivo (Todos los inscritos)',
            'role' => 'Por Rol',
            default => 'Individual',
        };
    }
}
