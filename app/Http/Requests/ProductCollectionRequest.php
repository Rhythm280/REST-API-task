<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Http\Exception\HttpResponseException;

class ProductCollectionRequest extends FormRequest
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
            'collection_name' => 'required|exists:App\Models\Collections,name',
            'product_id' => 'required|exists:App\Models\Products,id',
        ];
    }

    public function messages()
    {
        return [
            'collection_name.required' => 'Collection name is required',
            'product_id.required' => 'Product ID is required',
            'collection_name.exists' => 'Collection does not exist',
            'product_id.exists' => 'Product does not exist',
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], 422));
    }
}
