<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules() :array
    {
        return [
            'client.id' => 'required|exists:clients,id',
            'employee.id' => 'required|exists:employees,id',
            'products' => 'required|array',
            'products.*.id' => 'nullable|exists:products,id',
            'products.*.name' => 'required|string',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.category_id' => 'required|exists:categories,id',
            'products.*.pivot.quantity' => 'required|integer|min:1',
            'order_date' => 'required|date',
            'status' => 'required|string',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'is_credit' => 'required|boolean',
            'is_paid' => 'required|boolean',
        ];
    }
}
