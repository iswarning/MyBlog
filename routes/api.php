<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;
use App\Http\Controllers\AuthController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'cors'], function(){
    Route::apiResource('post', Api\PostController::class)->except(['create','edit','show']);
    Route::apiResource('user', Api\UserController::class)->except(['create','edit','show']);
    Route::apiResource('category', Api\CategoryController::class)->except(['create','edit','show']);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'postLogin']);
    Route::post('signup', [AuthController::class, 'register']);
  
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
    });
});
