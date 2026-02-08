<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user)
                    ->whereNull('deleted_at'),
            ],
            'phone' => [
                'required',
                'max:10',
                Rule::unique('users')->ignore($this->user)
                    ->whereNull('deleted_at'),
            ],
            'password' => 'required|min:8',
            'role_id' => 'required|uuid|exists:roles,id',
        ];
    }

    public function messages() : array
    {
        return [
            'name.required' => 'The field name is required',
            'name.string' => 'The field name must be a string.',
            'name.max' => 'The field name may not be greater than 255 characters.',
            'email.required' => 'The field email is required.',
            'email.email' => 'The field email must be a valid email.',
            'email.unique' => 'The email has already been taken.',
            'phone.required' => 'The field phone is required',
            'phone.max' => 'The field phone may not be greater than 10 characters.',
            'password.required' => 'The field password is required.',
            'password.min' => 'The field password must be at least 8 characters.',
            'role_id.required' => 'The field role is required.',
            'role_id.uuid' => 'The field role must be a valid UUID.',
            'role_id.exists' => 'The role selected is invalid.',
        ];
    }
}