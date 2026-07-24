<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Region;
use App\Models\User;
// use App\Models\BusinessDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{

    public function register(RegisterRequest $request)
    {
        $user = new User();

        // ====================
        // PERSONAL
        // ====================
        $user->firstname          = $request->input('firstname');
        $user->middlename         = $request->input('middlename');
        $user->lastname           = $request->input('lastname');
        $user->nickname           = $request->input('nickname');
        $user->email              = $request->input('email');
        $user->password           = Hash::make($request->input('password'));
        $user->number             = $request->input('number');
        $user->barangay_id        = $request->input('barangay_id');
        $user->municipality_id    = $request->input('municipality_id');
        $user->province_id        = $request->input('province_id');




        // // ====================
        // // BUSINESS
        // // ====================
        // $user->business_name      = $request->input('business_name');
        // $user->business_category  = $request->input('business_category');
        // $user->business_type      = $request->input('business_type');
        // $user->business_address   = $request->input('business_address');
        // $user->business_barangay  = $request->input('business_barangay');
        // $user->business_city      = $request->input('business_city');
        // $user->business_province  = $request->input('business_province');
        // $user->business_number    = $request->input('business_number');
        // $user->days_of_operation  = $request->input('days_of_operation');
        // $user->from_time          = $request->input('from_time');
        // $user->to_time            = $request->input('to_time');

        // // Payment Method
        // $user->cash               = $request->boolean('cash');
        // $user->gcash              = $request->boolean('gcash');
        // $user->paymaya            = $request->boolean('paymaya');
        // $user->utang_ok           = $request->boolean('utang_ok');

        // $user->business_notes     = $request->input('business_notes');

        // // Social Media
        // $user->tiktok             = $request->input('tiktok');
        // $user->facebook           = $request->input('facebook');
        // $user->instagram          = $request->input('instagram');
        // $user->website            = $request->input('website');


        // // Business Requirements
        // $user->business_permit                  = $request->file('business_permit');
        // $user->store_front_photo                = $request->file('store_front_photo');
        // $user->bir_certificate_of_registration  = $request->file('bir_certificate_of_registration');
        // $user->dti_registration                 = $request->file('dti_registration');
        // $user->sec_registration                 = $request->file('sec_registration');
        // $user->sanitary_registration            = $request->file('sanitary_registration');


        // // Product & Services
        // $user->products                         = (array) $request->input('products');
        // $user->additional_product               = $request->input('additional_product');


        // token
        $user->api_token          = Str::random(60);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully',
        ]);
    }


    public function getAddressMaintenance()
    {
        return response()->json(
            Region::with('provinces.municipalities.barangays')->orderBy('region_name')->get()
        ); 
    }
}
