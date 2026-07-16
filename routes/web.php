<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('products', ProductController::class)->except(['show']);
Route::resource('customers', CustomerController::class)->except(['show']);

Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');

// no_inv mengandung karakter "/" (format INV/2507/0001), jadi parameter route
// perlu diizinkan menangkap slash lewat regex ".*", bukan hanya satu segmen.
Route::get('transactions/{transaction}', [TransactionController::class, 'show'])
    ->name('transactions.show')
    ->where('transaction', '.*');
