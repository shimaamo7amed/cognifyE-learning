<?php

namespace App\Jobs;

use App\Mail\InstructorAccepted;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendInstructorAcceptedEmail implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $email;
    public $name_en;
    public $password;

    public function __construct($email, $name_en, $password)
    {
        $this->email = $email;
        $this->name_en = $name_en;
        $this->password = $password;
    }

    public function handle(): void
    {
        Mail::to($this->email)
            ->send(new InstructorAccepted($this->password, $this->name_en, $this->email));
    }
}
