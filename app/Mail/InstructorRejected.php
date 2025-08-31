<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InstructorRejected extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $name_en;
    public $reason;

    public function __construct($name_en, $reason)
    {
        $this->name_en = $name_en;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Instructor Application Rejected')
                    ->view('mails.instructor_rejected')
                    ->with([
                        'name_en' => $this->name_en,
                        'reason' => $this->reason,
                    ]);
    }
}
