<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BillingController;

Route::redirect('/', '/login');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/data', [BillingController::class, 'data'])->name('billing.data');
    Route::get('/billing/{id}/details', [BillingController::class, 'show']);
    Route::get('/billing/{id}/download', [BillingController::class, 'download']);
});
