<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::group(['middleware' => 'api'], function ($routes) { //second method to difine controller
// });

Route::prefix('v1')->group(function(){
    Route::middleware('api')->group(function () {

        Route::controller(UserController::class)->prefix('auth')->group(function () {
            Route::post('register', 'register');
            Route::post('login', 'login');
            Route::get('profile', 'profile');
            Route::post('token-refresh', 'tokenRefresh');
            Route::get('logout', 'logout');
        });
    });
});

