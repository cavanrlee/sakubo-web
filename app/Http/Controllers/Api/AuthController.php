<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Models\BotNavMenu;
use App\Models\Menu;
use App\Models\User;
use App\Services\BusinessServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserDevices;


class AuthController extends Controller
{
    public function dashboard(Request $request)
    {
        return response()->json([
            'success'       => true,
            'message'       => 'Login successful.',
            'data' => [
                'user'                      => Auth::user(),
                'menu'                      => Menu::get(),
                'bot_nav_items'             => BotNavMenu::get(),
                'business_accnt_details'    => BusinessServices::Business()
            ]
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        // AUTHENTICATE USER
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $sessionToken = $request->session()->getId();

            // SAVED THE DEVICE OF USER
            UserDevices::updateOrCreate(
                [
                    'user_id'       => Auth::id(),
                    'device_id'     => $request->device_id,
                ],
                [
                    'device_name'   => $request->device_name,
                    'device_token'  => $sessionToken,
                    'updated_at'    => now(),
                ]
            );


            // SUCCESS RESPONSE
            return response()->json([
                'success'       => true,
                'message'       => 'Login successful.',
                'auth_token'    => $sessionToken,
                'data' => [
                    'user'                      => Auth::user(),
                    'menu'                      => Menu::get(),
                    'bot_nav_items'             => BotNavMenu::get(),
                    'business_accnt_details'    => BusinessServices::Business()
                ]
            ], 200);
        }
        else{
            // FAILED RESPONSE
            return response()->json([
                'success' => false,
                'error' => [
                    'code'    => 'INVALID_CREDENTIALS',
                    'message' => 'Validation failed.',
                    'fields'  => [ 
                        'password' => [
                            'The email or password is incorrect.'
                        ]
                    ] 
                ]
            ], 401);
        }
    }


    public function changePassword(ChangePasswordRequest $request)
    {
        User::where('user_id', (int) $request->input('user_id'))
        ->update([
            'password' => Hash::make($request->input('new_password'))
        ]);

        return response()->json([
            'message' => 'Password changed!'
        ], 200);
    }
    
    public function logout(Request $request)
    {
        if ($request->has('device_id')) {
            UserDevices::where('user_id', Auth::id())
                ->where('device_id', $request->input('device_id'))
                ->delete();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logged out'
        ], 200);
    }

}

