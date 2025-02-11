<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Mail\Mailables\Address;

class PasswordUpdated extends Mailable
{
    use Queueable;
    use SerializesModels;

    /** @var User */
    private $user;

    /**
     * Create a new message instance.
     *
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        $fromAddress = is_string($fromAddress) ? $fromAddress : 'example@example.de';
        $fromName = is_string($fromName) ? $fromName : 'Recipe Finder Team';


        return new Envelope(
            subject: 'Your password has been updated',
            from: new Address($fromAddress, $fromName),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return Content
     */
    public function content(): Content
    {
        $mailTemplate = 'emails.users.password_updated';
        $name = $this->user->name ?? '';

        return new Content(
            markdown: $mailTemplate,
            with: [
                'name' => $name
            ]
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
