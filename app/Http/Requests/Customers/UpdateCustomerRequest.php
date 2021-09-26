<?php

namespace App\Http\Requests\Customers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
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
            'groupId' => 'required|string',
            'customer_code' => 'required|string',
            'customer_name' => 'required|string',
            'email' => 'nullable|email',
            'tel' => 'nullable|string',
            'address' => 'nullable|string',
            'taxCode' => 'required|string',
            'description' => 'nullable|string',
            'birthday' => 'nullable|date',
            'avatar' => 'mimes:jpeg,png,jpg|max:20000'
        ];
    }
}
