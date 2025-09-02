<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . auth()->id(),
            'phone'      => 'required|string|min:11|max:15',
            'country'    => 'required|string|max:255',
            'government' => 'required|string|max:255',
        ];
    }
}
