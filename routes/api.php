<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("login", [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post("register", [\App\Http\Controllers\Api\AuthController::class, 'register']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });
    Route::post('/sign-out', [\App\Http\Controllers\Api\AuthController::class, 'signout']);
    Route::post('/store/settings', [\App\Http\Controllers\Api\SettingsController::class, 'index']);
    Route::post('/store/set-product', [\App\Http\Controllers\Api\ProductController::class, 'set_product']);
    Route::post('/add-cart', [\App\Http\Controllers\Api\ProductController::class, 'add_cart']);
    Route::get('/calc-cart', [\App\Http\Controllers\Api\ProductController::class, 'calc_cart']);
});
