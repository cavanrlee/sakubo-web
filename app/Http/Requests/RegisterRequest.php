<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname'       => 'required|string|max:255',
            'middlename'      => 'nullable|string|max:255',
            'lastname'        => 'required|string|max:255',
            'nickname'        => 'nullable|string|max:255',

            'email'           => 'required|email|unique:users,email',

            'password'        => [
                'required',
                'string',
                'min:8',
                'max:30',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).{8,30}$/',
            ],

            'number'          => 'required|string|min:11|max:20',

            'region_id'       => 'required|exists:table_region,region_id',
            'province_id'     => 'required|exists:table_province,province_id',
            'municipality_id' => 'required|exists:table_municipality,municipality_id',
            'barangay_id'     => 'required|exists:table_barangay,barangay_id',
        ];
    }

    public function messages(): array
    {
        return [
            // ===== PERSONAL =====
            'firstname.required' => 'Firstname is required.',
            'firstname.string'   => 'Firstname must be a valid string.',
            'firstname.max'      => 'Firstname is too long.',

            'middlename.string'  => 'Middlename must be a valid string.',
            'middlename.max'     => 'Middlename is too long.',

            'lastname.required'  => 'Lastname is required.',
            'lastname.string'    => 'Lastname must be a valid string.',
            'lastname.max'       => 'Lastname is too long.',

            'nickname.string'    => 'Nickname must be a valid string.',
            'nickname.max'       => 'Nickname is too long.',

            'email.required'     => 'Email is required.',
            'email.email'        => 'Invalid email format.',
            'email.unique'       => 'Email is already taken.',

            'password.required'  => 'Password is required.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.max'       => 'Password must not exceed 30 characters.',
            'password.regex'     => 'Password must contain at least one letter and one number.',

            'number.required'    => 'Number is required.',
            'number.min'         => 'Number must be at least 11 digits.',
            'number.max'         => 'Number is too long.',

            'region_id.required'       => 'Region is required.',
            'region_id.exists'         => 'Selected region is invalid.',

            'province_id.required'     => 'Province is required.',
            'province_id.exists'       => 'Selected province is invalid.',

            'municipality_id.required' => 'Municipality is required.',
            'municipality_id.exists'   => 'Selected municipality is invalid.',

            'barangay_id.required'     => 'Barangay is required.',
            'barangay_id.exists'       => 'Selected barangay is invalid.',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'error' => [
                    'code'      => 'VALIDATION_ERROR',
                    'message'   => 'Validation failed.',
                    'fields'    => $validator->errors()
                ]
            ], 422)
        );
    }
}
