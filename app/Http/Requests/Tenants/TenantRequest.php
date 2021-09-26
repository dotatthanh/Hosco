<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TenantRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'tenantCode' => 'required|string',
            'tel' => 'nullable|string',
            'website' => 'nullable|string',
            'subscription' => 'nullable|numeric',
            'subscription_date' => 'nullable|date',
            'displayOrder' => 'nullable|numeric',
            'username' => 'required|string',
            'password' => 'required|string',
            'account_email' => 'required|email',
            'displayName' => 'required|string',
            'emailToRevalidate' => 'nullable|string',
            //'isActive' => 'nullable|boolean',
            'isSystemAccount' => 'nullable|boolean',
            'comment' => 'nullable|string',
        ];
    }
}
