<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Models\BotNavMenu;
use App\Models\Menu;
use App\Models\User;
use App\Models\UserDevices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Dashboard
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard loaded successfully.',
            'data' => [
                'user' => User::with([
                    'BusinessDetails.barangay',
                    'BusinessDetails.municipality',
                    'BusinessDetails.province',
                ])->find(Auth::id()),

                'menu' => Menu::get(),

                'bot_nav_menu' => BotNavMenu::get(),
            ],
        ], 200);
    }


    /**
     * Login
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email','password']);

        // Authenticate user
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'Validation failed.',
                    'fields' => [
                        'password' => [
                            'The email or password is incorrect.',
                        ],
                    ],
                ],
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Get User
        |--------------------------------------------------------------------------
        */
        $user = Auth::user();
        // Create Sanctum Personal Access Token
        $token = $user->createToken('mobile-app')->plainTextToken;

        /*
        |--------------------------------------------------------------------------
        | Save Device
        |--------------------------------------------------------------------------
        */
        if ($request->filled('device_id')) {
            UserDevices::updateOrCreate(
                [
                    'user_id'       => Auth::id(),
                    'device_id'     => $request->input('device_id'),
                ],
                [
                    'device_name'   => $request->input('device_name'),
                    'device_token'  => $token,
                    'updated_at'    => now(),
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Login Response
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user'          => User::with(['BusinessDetails.barangay','BusinessDetails.municipality','BusinessDetails.province'])->find(Auth::id()),
                'menu'          => Menu::get(),
                'bot_nav_menu'  => BotNavMenu::get(),
                'token'         => $token,
            ],
        ], 200);
    }


    /**
     * Change Password
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        User
        ::where('user_id', (int) $request->input('user_id'))
        ->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed!',
        ], 200);
    }


    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        if ($request->filled('device_id')) {
            UserDevices::where('user_id', Auth::id())
            ->where('device_id', $request->input('device_id'))
            ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ], 200);
    }
}
