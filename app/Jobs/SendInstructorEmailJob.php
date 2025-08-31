<?php

namespace App\Jobs;

use App\Models\Instructor;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendInstructorEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $instructor;

    public function __construct(Instructor $instructor)
    {
        $this->instructor = $instructor;
    }

    public function handle()
    {
        $emailData = collect($this->instructor->only([
            'name_en', 'name_ar', 'phone', 'email', 'linkedIn', 'message', 'experince'
        ]))->merge([
            'cv'         => asset('storage/' . $this->instructor->cv),
            'image'      => asset('storage/' . $this->instructor->image),
            'created_at' => $this->instructor->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->instructor->updated_at->format('Y-m-d H:i:s'),
        ])->toArray();

        Mail::send('mails.instructors', compact('emailData'), function ($message) {
            $message->to('shimaa0mohamed19@gmail.com')
                ->subject('New Contact Form Submission');
        });
    }
}
