<?php

use Illuminate\Support\Facades\Route;

// Login page
Route::get('/', function () {
    return view('login');
})->name('login');

// Dashboard page
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Customer page
Route::get('/customer', function () {
    return view('customer');
});

// Products/Inventory page
Route::get('/inventory', function () {
    return view('inventory');
});

// POS page
Route::get('/pos', function () {
    return view('pos');
});

// Quotations page
Route::get('/quotations', function () {
    return view('quotations');
});

// Sales Report page
Route::get('/report', function () {
    return view('report');
});