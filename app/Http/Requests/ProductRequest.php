<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'sku' => 'required|string|max:50',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_name' => 'required|exists:categories,name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'sku.required' => 'SKU is required',
            'name.max' => 'Name must be less than 50 characters',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'category_name.required' => 'Category name is required',
            'category_name.exists' => 'Category does not exist',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], 422));
    }
}
