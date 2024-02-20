<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReceptionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $first_name;
    public $last_name;
    public $email;
    public $subject;
    public $content;

    /**
     * Create a new message instance.
     */
    public function __construct($first_name, $last_name, $email, $subject, $content)
    {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '問い合わせを受け付けました',
            to: $this->email,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'contactReception',
            with: ([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'subject' => $this->subject,
                'content' => $this->content,
            ]),
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
