<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendNote extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $note;
    public $noteTitle;
    public $senderEmail;
    public $userName;
    public $userEmail;

    public function __construct($note, $noteTitle, $senderEmail, $userName, $userEmail)
    {
        $this->note = $note;
        $this->noteTitle = $noteTitle;
        $this->senderEmail = $senderEmail;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
    }

    /**
     * Build message.
     */
    public function build()
    {
        // return $this->view('user.SubmitTicket')
        //             ->subject('Ticket Submission');

        $recipients = [$this->senderEmail];


        // $attachments = [];
        // if ($this->ticket->images) {
        //     foreach ($this->ticket->images as $image) {
        //         $attachments[] = storage_path('storage/tickets/' . $image->t_image);
        //     }
        // }

        $email = $this
            ->to($recipients)
            ->subject($this->noteTitle)
            ->view('admin.sendNote', ['note' => $this->note, 'userName' => $this->userName, 'userEmail' => $this->userEmail]);

        // foreach ($attachments as $attachment) {
        //     $email->attachFromPath($attachment);
        // }

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
