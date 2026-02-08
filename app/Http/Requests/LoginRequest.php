<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'device' => 'required|string',
        ];
    }

    public function messages() : array
    {
        return [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email field must be a valid email.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password field must be at least 8 characters.',
            'device.required' => 'The device field is required.',
        ];
    }
}