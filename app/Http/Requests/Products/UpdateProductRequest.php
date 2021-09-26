<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'minInStock' => 'required|numeric',
            'maxInStock' => 'required|string',
            'product_name' => 'required|string',
            'product_code' => 'required|string',
            'groupId' => 'required|string',
            'unitPrice' => 'required|string',
            'purchasePrice' => 'required|string',
            'unit' => 'required|string',
            'noteImport' => 'nullable|string',
            'noteOrder' => 'nullable|string',
            'description' => 'nullable|string',
            'picture' => 'mimes:jpeg,png,jpg|max:20000'
        ];
    }
}
