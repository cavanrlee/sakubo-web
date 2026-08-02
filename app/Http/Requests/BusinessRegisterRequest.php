<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BusinessRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            // ===== BUSINESS (ONLY IF BUSINESS ACCOUNT) =====
            'business_name'         => 'required|string|max:255',
            'business_category'     => 'required|string|max:255',
            'business_type'         => 'required|string|max:255',

            'business_email'        => 'nullable|email|unique:business_details,email',
            'business_website'      => 'nullable|string|max:255',
            'business_address'      => 'required|string|max:255',

            'region_id'             => 'required|exists:table_region,region_id',
            'province_id'           => 'required|exists:table_province,province_id',
            'municipality_id'       => 'required|exists:table_municipality,municipality_id',
            'barangay_id'           => 'required|exists:table_barangay,barangay_id',


            // ===== OPERATING HOURS =====
            'days_of_operation'     => 'required|array',
            'open_time'             => 'required|date_format:H:i',
            'close_time'            => 'required|date_format:H:i|after:open_time',


            'business_notes'        => 'nullable|string',


            // ===== PAYMENT METHODS =====
            'business_services'     => 'array',

            // ===== SERVICE OPTIONS =====
            'pawyment_accepted'     => 'array',


            // ===== SOCIAL MEDIA =====
            'tiktok'                => 'nullable|string|max:255',
            'facebook'              => 'nullable|string|max:255',
            'instagram'             => 'nullable|string|max:255',
            'website'               => 'nullable|url|max:255',


            // ===== FILES =====
            'business_permit'                 => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'store_front_photo'               => 'required|image|max:2048',
            'bir_certificate_of_registration' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'dti_registration'                => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'sec_registration'                => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'sanitary_registration'           => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',


            // ===== PRODUCTS =====
            'products'                        => 'required|array',
            'products.*'                      => 'string|max:255',
            'additional_product'              => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            // ===== BUSINESS =====
            'business_name.required'        => 'Business name is required.',
            'business_name.max'             => 'Business name is too long.',

            'business_category.required'    => 'Business category is required.',
            'business_category.max'         => 'Business category is too long.',

            'business_type.required'        => 'Business type is required.',
            'business_type.max'             => 'Business type is too long.',

            'business_email.email'          => 'Invalid business email format.',
            'business_email.unique'         => 'Business email is already taken.',

            'business_website.string'       => 'Business website must be a valid text string.',
            'business_website.max'          => 'Business website is too long.',

            'business_address.required'     => 'Business address is required.',
            'business_address.max'          => 'Business address is too long.',

            'region_id.required'            => 'Region is required.',
            'region_id.exists'              => 'Selected region is invalid.',
            
            'province_id.required'          => 'Province is required.',
            'province_id.exists'            => 'Selected province is invalid.',

            'municipality_id.required'      => 'Municipality is required.',
            'municipality_id.exists'        => 'Selected municipality is invalid.',

            'barangay_id.required'          => 'Barangay is required.',
            'barangay_id.exists'            => 'Selected barangay is invalid.',


            // ===== OPERATING HOURS =====
            'days_of_operation.required'        => 'Days of operation is required.',
            'days_of_operation.array'           => 'Days of operation is not a array',

            'open_time.required'                => 'Open time is required.',
            'open_time.date_format'             => 'Open time must be in HH:MM format.',

            'close_time.required'               => 'Close time is required.',
            'close_time.date_format'            => 'Close time must be in HH:MM format.',
            'close_time.after'                  => 'Close time must be after from time.',


            'business_notes.string' => 'Business notes must be a valid string.',


            // ===== PAYMENT METHODS =====
            'cash.boolean'          => 'Cash must be true or false.',
            'gcash.boolean'         => 'Gcash must be true or false.',
            'paymaya.boolean'       => 'Paymaya must be true or false.',
            'utang_ok.boolean'      => 'Utang_ok must be true or false.',


            // ===== SERVICE OPTIONS =====
            'delivery.boolean'      => 'Delivery must be true or false.',
            'meetup.boolean'        => 'Meetup must be true or false.',
            'pickup.boolean'        => 'Pickup must be true or false.',


            // ===== SOCIAL MEDIA =====
            'tiktok.max'            => 'Tiktok is too long.',
            'facebook.max'          => 'Facebook is too long.',
            'instagram.max'         => 'Instagram is too long.',
            'website.url'           => 'Website must be a valid URL.',
            'website.max'           => 'Website is too long.',


            // ===== FILES =====
            'business_permit.required'      => 'Business permit is required.',
            'business_permit.mimes'         => 'Business permit must be jpg, jpeg, png, or pdf.',
            'business_permit.max'           => 'Business permit file is too large.',

            'store_front_photo.required'    => 'Store front photo is required.',
            'store_front_photo.image'       => 'Store front photo must be an image.',
            'store_front_photo.mimes'       => 'Store front photo must be jpg, jpeg, or png.',
            'store_front_photo.max'         => 'Store front photo is too large.',

            'bir_certificate_of_registration.mimes' => 'BIR certificate must be jpg, jpeg, png, or pdf.',
            'bir_certificate_of_registration.max'   => 'BIR certificate file is too large.',

            'dti_registration.mimes'        => 'DTI registration must be jpg, jpeg, png, or pdf.',
            'dti_registration.max'          => 'DTI registration file is too large.',

            'sec_registration.mimes'        => 'SEC registration must be jpg, jpeg, png, or pdf.',
            'sec_registration.max'          => 'SEC registration file is too large.',

            'sanitary_registration.mimes'   => 'Sanitary registration must be jpg, jpeg, png, or pdf.',
            'sanitary_registration.max'     => 'Sanitary registration file is too large.',


            // ===== PRODUCTS =====
            'products.required'             => 'Products are required.',
            'products.array'                => 'Products must be an array.',
            'products.min'                  => 'At least one product is required.',

            'products.*.string'             => 'Each product must be a valid string.',
            'products.*.max'                => 'Product name is too long.',

            'additional_product.string'     => 'Additional product must be a valid string.',
            'additional_product.max'        => 'Additional product is too long.',
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
