<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function(){
    return view('home');
});

/* Auth System */
Route::get('/login',[AuthController::class, 'login'])->name('login');
Route::post('/login',[AuthController::class, 'postLogin'])->name('postLogin');
Route::get('/logout',[AuthController::class, 'logout'])->name('logout');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister'])->name('postRegister');

/* Backend System */
Route::group(['prefix' => 'backend','middleware' => 'admin'], function(){

    Route::get('/post', function () {
        return view('backend.post');
    })->name('posts');

    Route::get('/user', function () {
        return view('backend.user');
    })->name('users');
});