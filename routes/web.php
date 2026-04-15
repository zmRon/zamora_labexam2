<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', ProductController::class)->except('show');
    Route::resource('orders', OrderController::class);

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
    Route::patch('/payments/{payment}/unpay', [PaymentController::class, 'unpay'])->name('payments.unpay');

});

require __DIR__ . '/auth.php';
