<?php

namespace App\Http\Requests\Auth;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required|string|min:3|max:25",
            "userName" => "required|string|unique:users,userName",
            "email" => "required|email|unique:users,email",
            'password' => [
                'required',
                'string',
                'min:10',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'confirmed'
            ],
            "phone" => "required|min_digits:11|max_digits:15",
            "gender" => "required|string",
            "country" => "required|string",
            "government" => "required|string",
        ];
    }



}
