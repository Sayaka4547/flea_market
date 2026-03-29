<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;

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

Route::get('/', [ItemController::class, 'index'])->name('index');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/mypage/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/mypage', function () {
    return view('mypage');
})->middleware('auth')->name('mypage');

Route::get('/sell', [CategoryController::class, 'create']);