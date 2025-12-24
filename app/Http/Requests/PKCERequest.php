<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PKCERequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => 'required',
            'code_challenge' => 'required',
            'code_challenge_method' => 'required|in:S256',
            'redirect_uri' => 'required|url',
        ];
    }
    
    public function messages()
    {
        return [
            'client_id.required' => 'El client_id es requerido',
            'code_challenge.required' => 'El code_challenge es requerido',
            'code_challenge_method.required' => 'El code_challenge_method es requerido',
            'redirect_uri.required' => 'El redirect_uri es requerido',
            'redirect_uri.url' => 'El redirect_uri debe ser una URL válida',
        ];
    }
}