<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;


Route::post('/login', [AuthController::class, 'login']);

    Route::prefix('customers')->controller(CustomerController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::put('/{customer}', 'update');
            Route::delete('/', 'destroy');
        });