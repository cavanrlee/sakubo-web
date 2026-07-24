<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SMSController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/optimize-me', function() {
    Artisan::call('config:cache'); // Merges all config files into one
    Artisan::call('route:cache');  // Compiles all routes into a fast-loading array
    Artisan::call('view:cache');   // Pre-compiles all Blade templates
    return "Optimization Complete!";
});

Route::middleware(['web', 'api.key'])->group(function () {

    // PUBLIC ROUTES
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/send-otp', [SMSController::class, 'sendOTP']);
    Route::post('/otp-validation', [SMSController::class, 'OTPValidation']);
    Route::get('/address-maintenance', [RegisterController::class, 'getAddressMaintenance']);


    // PROTECTED ROUTES
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        Route::get('/dashboard', [AuthController::class, 'dashboard']);
    });

});


Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'Unauthorized',
    ], 401);
});

