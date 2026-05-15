<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;  // ← correct namespace

// ── Public ─────────────────────────────────────
Route::get('/', fn() => view('login'))->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Protected (must be logged in) ──────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',  fn() => view('dashboard'));
    Route::get('/customer',   fn() => view('customer'));
    Route::get('/inventory',  fn() => view('inventory'));
    Route::get('/pos',        fn() => view('pos'));
    Route::get('/quotations', fn() => view('quotations'));
    Route::get('/report',     fn() => view('report'));
});
