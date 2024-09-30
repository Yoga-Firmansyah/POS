<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExpenditureController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/refresh', [AuthController::class, 'refresh'])->name('auth.refresh');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', [AuthController::class, 'getUser'])->name('auth.user');
    Route::get('/check', [AuthController::class, 'checkTokenExpired'])->name('auth.check');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::resource('/categories', CategoryController::class);
    Route::resource('/products', ProductController::class);
    Route::resource('/purchases', PurchaseController::class);
    Route::resource('/sales', SaleController::class);
    Route::resource('/expenditures', ExpenditureController::class);
    Route::resource('/users', UserController::class);
});