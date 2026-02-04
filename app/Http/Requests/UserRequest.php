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
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.max' => 'El campo nombre no debe exceder los 255 caracteres.',
            'email.required' => 'El campo email es obligatorio.',
            'email.email' => 'El campo email debe ser un email valido.',
            'email.unique' => 'El email ya está en uso.',
            'phone.required' => 'El campo teléfono es obligatorio.',
            'phone.max' => 'El campo teléfono no debe exceder los 10 caracteres.',
            'password.required' => 'El campo password es obligatorio.',
            'password.min' => 'El campo password debe tener al menos 8 caracteres.',
            'role_id.required' => 'El campo rol es obligatorio.',
            'role_id.uuid' => 'El campo rol debe ser un UUID válido.',
            'role_id.exists' => 'El rol seleccionado no es válido.',
        ];
    }
}