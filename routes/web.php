<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CompanyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group( function () {
    Route::get('login', [AuthController::class,'loginPage'])->name('login');
    Route::post('login', [AuthController::class,'login'])->name('login.post');
    Route::get('logout', [AuthController::class,'logout'])->name('logout');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('test-company', [CompanyController::class, 'index']);
