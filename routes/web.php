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