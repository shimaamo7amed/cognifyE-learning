<?php

namespace App\Http\Requests\Forms;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "fullName" => "required|string|min:3|max:255",
            "email" => "required|email|max:255",
            "phone" => "required|string|min:11|max:20",
            "message" => "required|string",
        ];
    }
}
