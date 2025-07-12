<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class NotifyTokenGmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */


     private $name;
     private $token;

    public function __construct($name,$token)
    {
        $this -> name = $name;
        $this -> token = $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('gabrielhenrique9253@gmail.com', 'Onfly'),
            subject: 'Token de Acesso ao Sistema',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'token',
            with:['name' => $this -> name, 'token' => $this -> token]
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
