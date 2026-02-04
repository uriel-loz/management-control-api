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
            'role.required' => 'El campo rol es obligatorio.',
            'role.string' => 'El campo rol debe ser una cadena de texto.',
            'role.max' => 'El campo rol no debe exceder los 75 caracteres.',
            'role.unique' => 'El rol ya está en uso.',
            'modules.required' => 'El campo módulos es obligatorio.',
            'modules.array' => 'El campo módulos debe ser un array.',
            'modules.min' => 'El campo módulos debe tener al menos un elemento.',
            'modules.*.uuid' => 'Cada permiso debe ser un UUID válido.',
            'modules.*.exists' => 'Algunos permisos no son válidos.',
        ];
    }
}