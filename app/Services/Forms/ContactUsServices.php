<?php

namespace  App\Services\Forms;

use App\Models\ContactUs;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;


class ContactUsServices
{
    public static function ContactUsForm(array $array)
    {
        $contact = ContactUs::create($array);
        // dd($data);
        $emailData = [
            'fullName'    => $contact->fullName,
            'phone'   => $contact->phone,
            'email'   => $contact->email,
            'message' => $contact->message,
        ];
        Mail::send('mails.contact_us', ['emailData' => $emailData], function ($message) {
        $message->to('shimaa0mohamed19@gmail.com')
            ->subject('New Contact Form Submission');
        });

        Notification::make()
            ->title('New Contact Form Submission')
            ->body("A new message from {$contact->fullName}.")
            ->send();
        return $contact;
    }
}