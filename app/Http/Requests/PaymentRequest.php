<?php

namespace App\Http\Requests;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(PaymentStatus::class)],
            'method' => ['required', 'string', Rule::enum(PaymentMethod::class)],
            'quantity' => 'required|numeric|min:0',
            'order_id' => 'required|uuid|exists:orders,id',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'The field status is required.',
            'status.string' => 'The field status must be a string.',
            'status.enum' => 'The selected status is invalid. Valid values are: pending, processing, completed, failed, refunded.',
            'method.required' => 'The field method is required.',
            'method.string' => 'The field method must be a string.',
            'method.enum' => 'The selected method is invalid. Valid values are: credit_card, debit_card, paypal, bank_transfer, cash.',
            'quantity.required' => 'The field quantity is required.',
            'quantity.numeric' => 'The field quantity must be a number.',
            'quantity.min' => 'The field quantity must be at least 0.',
            'order_id.required' => 'The field order is required.',
            'order_id.uuid' => 'The field order must be a valid UUID.',
            'order_id.exists' => 'The selected order does not exist.',
        ];
    }
}
