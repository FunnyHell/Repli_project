<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'client.surname' => 'required|string|max:255',
            'client.name' => 'required|string|max:255',
            'client.sex' => 'required|boolean',
            'client.age' => 'nullable|integer|min:0',
            'client.phone' => 'nullable|string|max:20',
            'client.email' => 'nullable|email|max:255',
            'client.address' => 'nullable|string|max:255',

            'employee.surname' => 'required|string|max:255',
            'employee.name' => 'required|string|max:255',

            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.category_id' => 'required|integer|exists:categories,id',
            'products.*.pivot.quantity' => 'required|integer|min:1',

            'total' => 'required|numeric|min:0',
            'order_date' => 'required|date',
            'status' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'is_credit' => 'required|boolean',
            'is_paid' => 'required|boolean',
        ];
    }
}
