<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BusinessAccountController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SMSController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/


/*
|--------------------------------------------------------------------------
| Optimization
|--------------------------------------------------------------------------
*/

Route::get('/optimize-me', function () {

    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return "Optimization Complete!";
});


/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['api.key'])->group(function () {

    Route::post('/login', [
        AuthController::class,
        'login'
    ])->name('login');

    Route::post('/register', [
        RegisterController::class,
        'register'
    ]);

    Route::post('/send-otp', [
        SMSController::class,
        'sendOTP'
    ]);

    Route::post('/otp-validation', [
        SMSController::class,
        'OTPValidation'
    ]);

    Route::get('/address-maintenance', [
        RegisterController::class,
        'getAddressMaintenance'
    ]);


    /*
    |--------------------------------------------------------------------------
    | PROTECTED ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth:sanctum'])->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [
            AuthController::class,
            'dashboard'
        ]);

        Route::post('/change-password', [
            AuthController::class,
            'changePassword'
        ]);

        Route::post('/logout', [
            AuthController::class,
            'logout'
        ]);


        /*
        |--------------------------------------------------------------------------
        | Business Accounts
        |--------------------------------------------------------------------------
        */

        Route::prefix('business-accounts')->group(function () {

            Route::get('/', [
                BusinessAccountController::class,
                'index'
            ]);

            Route::post('/create', [
                BusinessAccountController::class,
                'store'
            ]);

            Route::get('/edit/{id}', [
                BusinessAccountController::class,
                'show'
            ]);

            Route::match(['put', 'patch'], '/update/{id}', [
                BusinessAccountController::class,
                'update'
            ]);

            Route::delete('/delete/{id}', [
                BusinessAccountController::class,
                'destroy'
            ]);
        });
    });
});


/*
|--------------------------------------------------------------------------
| FALLBACK
|--------------------------------------------------------------------------
|
| This only handles routes that do not exist.
|
*/

Route::fallback(function () {

    return response()->json([
        'success' => false,
        'message' => 'Route not found.',
    ], 404);

});

