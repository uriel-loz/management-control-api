<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => ['required', 'uuid', 'exists:products,id'],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'El id del producto es requerido.',
            'product_id.uuid' => 'El id del producto debe ser un UUID válido.',
            'product_id.exists' => 'El producto especificado no existe.',
            'images.required' => 'Debe enviar al menos una imagen.',
            'images.array' => 'El campo images debe ser un arreglo.',
            'images.min' => 'Debe enviar al menos una imagen.',
            'images.*.required' => 'Cada imagen es requerida.',
            'images.*.image' => 'Cada archivo debe ser una imagen válida.',
            'images.*.mimes' => 'Cada imagen debe ser de tipo: jpeg, png, jpg, webp o gif.',
            'images.*.max' => 'Cada imagen no debe superar los 2 MB.',
        ];
    }
}
