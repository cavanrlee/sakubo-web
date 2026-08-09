<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Region;
use App\Models\User;
// use App\Models\BusinessDetail;
use Illuminate\Support\Facades\Hash;

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

        $user->save();

        // Create Sanctum personal access token
        $token = $user->createToken('mobile-app')->plainTextToken;

        // token
        $user->api_token          = $token;
        $user->save();

        return response()->json([
            'success'   => true,
            'message'   => 'Account created successfully',
            // 'token'     => $token
        ]);
    }


    public function getAddressMaintenance()
    {
        return response()->json(
            Region::with('provinces.municipalities.barangays')->orderBy('region_name')->get()
        ); 
    }
}
