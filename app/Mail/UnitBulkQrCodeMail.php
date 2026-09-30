<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UnitBulkQrCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Collection $units,
        public string $pdfPath,
        public string $format = 'label',
        public ?string $message = null
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Bulk Unit QR Codes - {$this->units->count()} units ({$this->format})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.unit-bulk-qr-code',
            with: [
                'units' => $this->units,
                'format' => $this->format,
                'message' => $this->message,
                'count' => $this->units->count(),
            ]
        );
    }

    public function attachments(): array
    {
        return [
            [
                'path' => storage_path("app/public/{$this->pdfPath}"),
                'as' => "Bulk-Units-{$this->format}.pdf",
                'mime' => 'application/pdf',
            ]
        ];
    }
}
