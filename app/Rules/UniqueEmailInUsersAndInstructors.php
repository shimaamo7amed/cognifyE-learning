<?php
 
namespace App\Rules;


use App\Models\User;
use App\Models\Instructor;
use Illuminate\Contracts\Validation\Rule;

class UniqueEmailInUsersAndInstructors implements Rule
{
    public function passes($attribute, $value)
    {
        return !(
            User::where('email', $value)->exists() ||
            Instructor::where('email', $value)->exists()
        );
    }

    public function message()
    {
        return 'Email already exists.';
    }
}

