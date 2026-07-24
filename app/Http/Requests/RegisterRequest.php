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

            // // ===== BUSINESS (ONLY IF BUSINESS ACCOUNT) =====
            // 'business_name'     => 'required_if:account_type,business|string|max:255',
            // 'business_category' => 'required_if:account_type,business|string|max:255',
            // 'business_type'     => 'required_if:account_type,business|string|max:255',

            // 'business_email'    => 'required_if:account_type,business|email|unique:businesses,email',
            // 'business_website'  => 'nullable|url|max:255',

            // 'business_address'  => 'required_if:account_type,business|string|max:255',
            // 'business_barangay' => 'required_if:account_type,business|string|max:255',
            // 'business_city'     => 'required_if:account_type,business|string|max:255',
            // 'business_province' => 'required_if:account_type,business|string|max:255',

            // 'business_number'   => 'required_if:account_type,business|string|min:11|max:20',


            // // ===== OPERATING HOURS =====
            // 'days_of_operation'   => 'required_if:account_type,business|string|max:20',

            // 'from_time' => 'required_if:account_type,business|date_format:H:i',
            // 'to_time'   => 'required_if:account_type,business|date_format:H:i|after:from_time',

            // 'business_notes' => 'nullable|string',


            // // ===== PAYMENT METHODS =====
            // 'cash'     => 'boolean',
            // 'gcash'    => 'boolean',
            // 'paymaya'  => 'boolean',
            // 'utang_ok' => 'boolean',


            // // ===== SERVICE OPTIONS =====
            // 'delivery'  => 'boolean',
            // 'meetup'    => 'boolean',
            // 'pickup'    => 'boolean',


            // // ===== SOCIAL MEDIA =====
            // 'tiktok'    => 'nullable|string|max:255',
            // 'facebook'  => 'nullable|string|max:255',
            // 'instagram' => 'nullable|string|max:255',
            // 'website'   => 'nullable|url|max:255',


            // // ===== FILES =====
            // 'business_permit'                 => 'required_if:account_type,business|file|mimes:jpg,jpeg,png,pdf|max:2048',
            // 'store_front_photo'               => 'required_if:account_type,business|image|max:2048',

            // 'bir_certificate_of_registration' => 'required_if:account_type,business|file|mimes:jpg,jpeg,png,pdf|max:2048',
            // 'dti_registration'                => 'required_if:account_type,business|file|mimes:jpg,jpeg,png,pdf|max:2048',
            // 'sec_registration'                => 'required_if:account_type,business|file|mimes:jpg,jpeg,png,pdf|max:2048',
            // 'sanitary_registration'           => 'required_if:account_type,business|file|mimes:jpg,jpeg,png,pdf|max:2048',


            // // ===== PRODUCTS =====
            // 'products'                        => 'required_if:account_type,business|array',
            // 'products.*'                      => 'string|max:255',

            // 'additional_product'              => 'nullable|string|max:255',
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

            // // ===== BUSINESS =====
            // 'business_name.required_if' => 'Business name is required.',
            // 'business_name.max'         => 'Business name is too long.',

            // 'business_category.required_if' => 'Business category is required.',
            // 'business_category.max'         => 'Business category is too long.',

            // 'business_type.required_if' => 'Business type is required.',
            // 'business_type.max'         => 'Business type is too long.',

            // 'business_email.required_if' => 'Business email is required.',
            // 'business_email.email'       => 'Invalid business email format.',
            // 'business_email.unique'      => 'Business email is already taken.',

            // 'business_website.url' => 'Business website must be a valid URL.',
            // 'business_website.max' => 'Business website is too long.',

            // 'business_address.required_if' => 'Business address is required.',
            // 'business_address.max'         => 'Business address is too long.',

            // 'business_barangay.required_if' => 'Business barangay is required.',
            // 'business_barangay.max'         => 'Business barangay is too long.',

            // 'business_city.required_if' => 'Business city is required.',
            // 'business_city.max'         => 'Business city is too long.',

            // 'business_province.required_if' => 'Business province is required.',
            // 'business_province.max'         => 'Business province is too long.',

            // 'business_number.required_if' => 'Business number is required.',
            // 'business_number.min'         => 'Business number must be at least 11 digits.',
            // 'business_number.max'         => 'Business number is too long.',


            // // ===== OPERATING HOURS =====
            // 'days_of_operation.required_if' => 'Days of operation is required.',
            // 'days_of_operation.max'         => 'Days of operation is too long.',

            // 'from_time.required_if' => 'From time is required.',
            // 'from_time.date_format' => 'From time must be in HH:MM format.',

            // 'to_time.required_if' => 'To time is required.',
            // 'to_time.date_format' => 'To time must be in HH:MM format.',
            // 'to_time.after'       => 'To time must be after from time.',

            // 'business_notes.string' => 'Business notes must be a valid string.',


            // // ===== PAYMENT METHODS =====
            // 'cash.boolean'     => 'Cash must be true or false.',
            // 'gcash.boolean'    => 'Gcash must be true or false.',
            // 'paymaya.boolean'  => 'Paymaya must be true or false.',
            // 'utang_ok.boolean' => 'Utang_ok must be true or false.',


            // // ===== SERVICE OPTIONS =====
            // 'delivery.boolean' => 'Delivery must be true or false.',
            // 'meetup.boolean'   => 'Meetup must be true or false.',
            // 'pickup.boolean'   => 'Pickup must be true or false.',


            // // ===== SOCIAL MEDIA =====
            // 'tiktok.max' => 'Tiktok is too long.',
            // 'facebook.max' => 'Facebook is too long.',
            // 'instagram.max' => 'Instagram is too long.',
            // 'website.url' => 'Website must be a valid URL.',
            // 'website.max' => 'Website is too long.',


            // // ===== FILES =====
            // 'business_permit.required_if' => 'Business permit is required.',
            // 'business_permit.mimes'       => 'Business permit must be jpg, jpeg, png, or pdf.',
            // 'business_permit.max'         => 'Business permit file is too large.',

            // 'store_front_photo.required_if' => 'Store front photo is required.',
            // 'store_front_photo.image'       => 'Store front photo must be an image.',
            // 'store_front_photo.mimes'       => 'Store front photo must be jpg, jpeg, or png.',
            // 'store_front_photo.max'         => 'Store front photo is too large.',

            // 'bir_certificate_of_registration.mimes' => 'BIR certificate must be jpg, jpeg, png, or pdf.',
            // 'bir_certificate_of_registration.max'   => 'BIR certificate file is too large.',

            // 'dti_registration.mimes' => 'DTI registration must be jpg, jpeg, png, or pdf.',
            // 'dti_registration.max'   => 'DTI registration file is too large.',

            // 'sec_registration.mimes' => 'SEC registration must be jpg, jpeg, png, or pdf.',
            // 'sec_registration.max'   => 'SEC registration file is too large.',

            // 'sanitary_registration.mimes' => 'Sanitary registration must be jpg, jpeg, png, or pdf.',
            // 'sanitary_registration.max'   => 'Sanitary registration file is too large.',


            // // ===== PRODUCTS =====
            // 'products.required_if' => 'Products are required.',
            // 'products.array'       => 'Products must be an array.',
            // 'products.min'         => 'At least one product is required.',

            // 'products.*.string' => 'Each product must be a valid string.',
            // 'products.*.max'    => 'Product name is too long.',

            // 'additional_product.string' => 'Additional product must be a valid string.',
            // 'additional_product.max'    => 'Additional product is too long.',
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
