<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'    => 'required|email|exists:users,email', 
            'password' => 'required|string|min:3',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'Email is required.',
            'email.email'       => 'Please provide a valid email address.',
            'email.exists'      => 'This email is not registered in our system.', 
            'password.required' => 'Password is required.',
            'password.min'      => 'Password must be at least 3 characters.',
        ];
    }


    
    /**
     * FORCE JSON ERROR RESPONSE (API STYLE)
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'error' => [
                    'code'      => 'VALIDATION_ERROR',
                    'message'   => 'Validation failed.',
                    'fields'    => $validator->errors(),
                ],
            ], 422)
        );
    }
}
