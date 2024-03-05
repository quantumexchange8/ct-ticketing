<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmitTicket extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $ticket;
    public $subject;
    public $senderEmail;
    public $emailSubject;

    public function __construct($ticket, $subject, $senderEmail, $emailSubject)
    {
        $this->ticket = $ticket;
        $this->subject = $subject;
        $this->senderEmail = $senderEmail;
        $this->emailSubject = $emailSubject;
    }

    /**
     * Build message.
     */
    public function build()
    {
        // return $this->view('user.SubmitTicket')
        //             ->subject('Ticket Submission');

        $recipients = ['amberljq00@gmail.com'];

        // $recipients = [$this->senderEmail];

        $attachments = [];
        if ($this->ticket->images) {
            foreach ($this->ticket->images as $image) {
                $attachments[] = storage_path('storage/tickets/' . $image->t_image);
            }
        }

        $email = $this
            ->to($recipients)
            ->subject($this->emailSubject)
            ->view('user.submitTicket', ['ticket' => $this->ticket]);

        foreach ($attachments as $attachment) {
            $email->attachFromPath($attachment);
        }

        return $email;
    }
    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Submit Ticket',
    //     );
    // }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         markdown: 'user.submitTicket',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
