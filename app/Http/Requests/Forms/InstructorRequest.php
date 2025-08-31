<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UniqueEmailInUsersAndInstructors;

class InstructorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
        "name_en"=>"required|string|min:3|max:50",
        "name_ar"=>"required|string|min:3|max:50",
        'email'=> ['required', 'email', new UniqueEmailInUsersAndInstructors],
        "phone"=>"required|string|min_digits:11|max_digits:15",
        "experince" => "required|string",
        "linkedIn" => "required|string|regex:/^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9_-]+\/?$/",
        "facebook" => "required|string|regex:/^https:\/\/www\.facebook\.com\/[a-zA-Z0-9\.\-_]+\/?$/",
        "message"=>"required",
        'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
        "image" => "required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048",

        ];
    }
}
