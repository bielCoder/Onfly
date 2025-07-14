<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class NotifyRecoveryGmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $link;
    private $name;

    /**
     * Create a new message instance.
     */
    public function __construct($email, $name,$link)
    {
        $this -> email = $email;
        $this -> name = $name;
        $this -> link = $link;
    }

    /**
     * Get the message envelope.
     */
     public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('gabrielhenrique9253@gmail.com', 'Onfly'),
            subject: 'Recuperação de Senha',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'recovery',
            with:['name' => $this -> name,'link' => $this -> link]
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
