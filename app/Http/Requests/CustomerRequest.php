<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            // 'opening_balance' => 'nullable|numeric',
        ];
        if ($this->isMethod('delete')) {
            $rules = [];    
            $rules['ids'] = 'required|string';
        }
        if ($this->isMethod('get')) {
            $rules = [];
            $rules['search'] = 'nullable|string|max:255';
            $rules['limit'] = 'nullable|integer|min:1|max:100';
        }

        return $rules;
    }
}
