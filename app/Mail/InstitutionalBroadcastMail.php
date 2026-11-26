<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstitutionalBroadcastMail extends Mailable
{
    use SerializesModels;

    /**
     * Create a new synchronous message instance (compatible con hosting compartido sin colas).
     *
     * @param  array<int, array{path: string, name: string, mime: string}>  $attachedFiles
     * @param  array<int, array{name: string, size_label: string, url: string}>  $downloadLinks
     */
    public function __construct(
        public string $emailSubject,
        public string $emailBody,
        public string $recipientName = 'Comunero(a)',
        public string $senderName = 'Consejo Comunitario Zanjón de Garrapatero',
        public array $attachedFiles = [],
        public array $downloadLinks = [],
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.institutional-broadcast',
            text: 'emails.institutional-broadcast-text',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->attachedFiles as $file) {
            if (! empty($file['path']) && file_exists($file['path'])) {
                $attachments[] = Attachment::fromPath($file['path'])
                    ->as($file['name'])
                    ->withMime($file['mime'] ?? 'application/octet-stream');
            }
        }

        return $attachments;
    }
}
