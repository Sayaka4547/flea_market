<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MypageController;

Route::get('/', [ItemController::class, 'index'])->name('index');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// マイページ・プロフィール
Route::get('/mypage/profile', [ProfileController::class, 'edit'])->middleware('auth')->name('profile');
Route::post('/mypage/profile', [ProfileController::class, 'update'])->middleware('auth')->name('profile.update');
Route::get('/mypage', [MypageController::class, 'index'])->middleware('auth')->name('mypage');

// 商品出品
Route::get('/sell', [CategoryController::class, 'create'])->middleware('auth');
Route::post('/sell', [ItemController::class, 'store'])->middleware('auth')->name('item.store');

// 商品詳細・いいね・コメント
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::post('/like/{item_id}', [LikeController::class, 'toggle'])->middleware('auth')->name('like.toggle');
Route::post('/comment/{item_id}', [CommentController::class, 'store'])->middleware('auth')->name('comment.store');

// 購入・住所変更
Route::get('/purchase/success', [PurchaseController::class, 'success'])
    ->middleware(['auth', 'verified'])
    ->name('purchase.success');

Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->middleware('auth')->name('purchase.index');
Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->middleware('auth')->name('purchase.store');

Route::get('/address/edit', [AddressController::class, 'edit'])->middleware('auth')->name('address.edit');
Route::put('/address/update', [AddressController::class, 'update'])->middleware('auth')->name('address.update');
