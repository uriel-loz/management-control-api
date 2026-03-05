<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return match($this->method()) {
            'POST' => $this->user()->can('create', User::class),
            'PUT', 'PATCH' => $this->user()->can('update', $this->route('user')),
            'DELETE' => $this->user()->can('delete', $this->route('user')),
            default => false,
        };
    }

    public function rules(): array
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
            'is_customer' => 'required|boolean',
            'password' => [
                'nullable',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'role_id' => 'required|uuid|exists:roles,id',
        ];
    }

    public function messages(): array
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
            'is_customer.required' => 'The field is_customer is required.',
            'is_customer.boolean' => 'The field is_customer must be a boolean.',
            'role_id.required' => 'The field role is required.',
            'role_id.uuid' => 'The field role must be a valid UUID.',
            'role_id.exists' => 'The role selected is invalid.',
        ];
    }
}