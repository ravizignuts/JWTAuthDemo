<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;
use App\Http\Controllers\V1\TodoController;
use App\Http\Controllers\V1\UserController;

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
Route::group(['prefix' => 'v1'], function () {
    Route::middleware('auth:api')->group(function () {
        Route::controller(UserController::class)->prefix('auth')->group(function () {
            Route::post('register', 'register')->withoutMiddleware('auth:api');
            Route::post('login', 'login')->withoutMiddleware('auth:api');
            Route::get('profile', 'profile');
            Route::post('token-refresh', 'tokenRefresh');
            Route::get('logout', 'logout');
        });
        Route::controller(TodoController::class)->prefix('todo')->group(function () {
            Route::get('list', 'list');
            Route::post('create', 'create');
            Route::put('update/{id}', 'update');
            Route::delete('delete/{id}', 'delete');
            Route::get('get/{id}', 'get');
        });
    });
});
