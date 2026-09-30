<?php

namespace App\Mail;

use App\Models\Unit;
use Barryvdh\DomPDF\PDF;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UnitQrCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Unit $unit,
        public PDF $pdf,
        public string $format = 'label',
        public ?string $message = null
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Unit QR Code - {$this->unit->unitName} ({$this->format})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.unit-qr-code',
            with: [
                'unit' => $this->unit,
                'format' => $this->format,
                'message' => $this->message,
            ]
        );
    }

    public function attachments(): array
    {
        return [
            $this->pdf->attach(
                'inline',
                [
                    'as' => "Unit-{$this->unit->unitName}-{$this->format}.pdf",
                    'mime' => 'application/pdf',
                ]
            ),
        ];
    }
}
