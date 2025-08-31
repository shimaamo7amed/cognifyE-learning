<?php

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('change-language');


Route::get('/test-mail', function () {
    Mail::to('shimaa0mohamed19@gmail.com')->send(new \App\Mail\InstructorAccepted('123456', 'Test User', 'test@example.com'));
    return 'Mail sent!';
});
