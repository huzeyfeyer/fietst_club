<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactMessageNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public ContactMessage $contactMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $replyToAddress = null;
        if (filter_var($this->contactMessage->email, FILTER_VALIDATE_EMAIL)) {
            $replyToAddress = [new Address($this->contactMessage->email, $this->contactMessage->name)];
        }

        return new Envelope(
            subject: 'Nieuw Contactbericht Ontvangen: ' . ($this->contactMessage->subject ?? 'Geen onderwerp'),
            replyTo: $replyToAddress
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact.notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
