<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class InstructorAccepted extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $password;
    public $name_en;
    public $email;

    public function __construct($password, $name_en, $email)
    {
        $this->password = $password;
        $this->email = $email;
        $this->name_en = $name_en;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Accepted',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.instructor_accepted',
            with: [
                'name_en' => $this->name_en,
                'email' => $this->email,
                'password' => $this->password,
            ]
        );
    }
}
