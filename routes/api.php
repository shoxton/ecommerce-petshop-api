<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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


Route::prefix('v1')->group(function () {

    Route::prefix('user')->name('user.')->group(function () {

        Route::post('create', [UserController::class, 'store'])->name('store');
        Route::get('/', [UserController::class, 'show'])->name('show')->middleware('auth:jwt');
        Route::put('edit', [UserController::class, 'update'])->name('update')->middleware('auth:jwt');
        Route::delete('/', [UserController::class, 'destroy'])->name('destroy')->middleware('auth:jwt');

        Route::post('login', [UserController::class, 'login'])->name('login');
        Route::get('logout', [])->name('logout');

        Route::post('forgot-password', [])->name('forgot-password');
        Route::post('reset-password-token', [])->name('reset-password-token');

        Route::get('orders', [])->name('orders')->middleware('auth:jwt');
    });


    Route::apiResource('product', ProductController::class);
});
