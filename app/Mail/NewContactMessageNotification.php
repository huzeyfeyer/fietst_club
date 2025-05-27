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
use Illuminate\Support\Facades\Log;

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
        // Hersteld om validReplyTo() te gebruiken
        return new Envelope(
            subject: 'Nieuw Contactbericht Ontvangen: ' . ($this->contactMessage->subject ?? 'Geen onderwerp'),
            replyTo: $this->validReplyTo()
        );
    }

    /**
     * Valideer het replyTo e-mailadres en retourneer het correcte formaat.
     *
     * @return array<int, \\Illuminate\\Mail\\Mailables\\Address>|null
     */
    private function validReplyTo(): ?array
    {
        $email = trim($this->contactMessage->email);
        $name = trim($this->contactMessage->name);

        // Loggen van de waarden die we gaan gebruiken
        Log::debug('validReplyTo in NewContactMessageNotification wordt uitgevoerd met:', [
            'raw_email' => $this->contactMessage->email,
            'trimmed_email' => $email,
            'raw_name' => $this->contactMessage->name,
            'trimmed_name' => $name,
        ]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Log::warning("Ongeldig reply-to e-mailadres in NewContactMessageNotification (na trim): {$email}. Oorspronkelijk: " . $this->contactMessage->email);
            return null; // Geen replyTo als e-mail ongeldig is
        }

        // Retourneer een array met een Address object.
        // Als $name leeg is na trimmen, wordt null gebruikt voor de naam in het Address object, wat geldig is.
        return [new Address($email, $name ?: null)];
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
     * @return array<int, \\Illuminate\\Mail\\Mailables\\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
