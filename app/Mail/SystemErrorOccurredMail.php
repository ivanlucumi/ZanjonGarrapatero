<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SystemErrorOccurredMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param  array{
     *     errorCode: string|int,
     *     errorMessage: string,
     *     errorMessageSummary: string,
     *     exceptionClass: string,
     *     file: string,
     *     line: int,
     *     url: string,
     *     method: string,
     *     ip: string,
     *     user: ?string,
     *     user_agent: string,
     *     environment: string,
     *     timestamp: string,
     *     trace: string
     * }  $errorData
     */
    public function __construct(public array $errorData) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $code = (string) ($this->errorData['errorCode'] ?? '500');
        $summary = $this->errorData['errorMessageSummary']
            ?? Str::limit((string) ($this->errorData['errorMessage'] ?? 'Excepción no controlada'), 45);

        return new Envelope(
            subject: "[ALERTA ERROR {$code}] {$summary} - Consejo Comunitario Zanjón de Garrapatero",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.system-error',
            text: 'emails.system-error-text',
            with: [
                'errorData' => $this->errorData,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
