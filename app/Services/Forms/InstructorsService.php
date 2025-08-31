<?php

namespace App\Services\Forms;

use App\Models\User;
use App\Models\Instructor;
use App\Jobs\SendInstructorEmailJob;
use Filament\Notifications\Notification;

class InstructorsService
{
    public function InstructorsForm(array $array)
    {
        foreach (['cv' => 'cvs', 'image' => 'images'] as $field => $folder) {
            if (request()->hasFile($field)) {
                $array[$field] = request()->file($field)->store($folder, 'public');
            }
        }
        $instructor = Instructor::create($array);
        dispatch(new SendInstructorEmailJob($instructor));
        Notification::make()
            ->title('New Contact Form Submission')
            ->body("A new message from {$instructor->name_en}.")
            ->send();

        return $instructor;
    }
    public function getInstructors()
    {
        return User::select('id', 'slug', 'name_en', 'name_ar', 'image', 'desc')->where('user_type', 'instructor')->get();
    }
    public function getInstructorBySlug($slug)
    {
        return User::select('id', 'slug', 'name_en', 'name_ar', 'image', 'desc', 'facebook', 'linkedIn')
            ->where('user_type', 'instructor')
            ->where('slug', $slug)
            ->with(['courses' => function ($query) {
                $query->with(['category', 'tags', 'videos', 'instructor']);
            }])
            ->first();
    }
}