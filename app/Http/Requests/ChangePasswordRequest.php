<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id'          => ['required'],
            'new_password'     => [
                'required',
                'string',
                'min:8',
                'max:30',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).{8,30}$/',
                'same:confirm_password'
            ],
            'confirm_password' => [
                'required',
                'string',
                'min:8',
                'max:30',
            ],
        ];
    }

    public function messages()
    {
        return [
            'new_password.same'     => 'Password does not match.',
            'new_password.regex'    => 'Password must contain at least 1 letter and 1 number.',
        ];
    }

    /**
     * FORCE JSON ERROR RESPONSE
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