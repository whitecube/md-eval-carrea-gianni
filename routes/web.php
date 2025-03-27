<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::view('login', 'login')->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'show'])->name('home');
    Route::post('/order', [OrderController::class, 'create'])->name('order.create');
    Route::post('/order/{order}', [OrderController::class, 'update'])->name('order.update');
});
