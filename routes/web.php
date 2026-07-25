<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\File;

// Lahat ng hindi API routes ay ibabato sa React index.html
Route::get('/{any?}', function () {
    $path = public_path('index.html');
    
    if (File::exists($path)) {
        return File::get($path);
    }
    
    abort(404);
})->where('any', '.*');
