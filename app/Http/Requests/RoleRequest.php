<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules() : array
    {
        return [
            'role' => [
                'required',
                'string',
                'max:75',
                Rule::unique('roles', 'name')
                    ->whereNull('deleted_at'),
            ],
            'modules' => 'required|array|min:1',
            'modules.*' => [
                'uuid',
                Rule::exists('permissions', 'id')->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages() : array
    {
        return [
            'role.required' => 'The role is required',
            'role.string' => 'The role must be a string.',
            'role.max' => 'The role may not be greater than 75 characters.',
            'role.unique' => 'The role has already been taken.',
            'modules.required' => 'The modules field is required.',
            'modules.array' => 'The modules must be an array.',
            'modules.min' => 'The modules must have at least one element.',
            'modules.*.uuid' => 'Each permission must be a valid UUID.',
            'modules.*.exists' => 'Some permissions are invalid.',
        ];
    }
}