<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SimilarityMatchFoundMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public array $payload)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ASTU Lost & Found: Potential Item Match Found'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.similarity-match-found'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
