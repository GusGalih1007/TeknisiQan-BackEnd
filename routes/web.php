<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group( function () {
    Route::get('login', [AuthController::class,'loginPage'])->name('login');
    Route::post('login', [AuthController::class,'login'])->name('login.post');
    Route::get('logout', [AuthController::class,'logout'])->name('logout');
});

Route::get('user', [UserController::class, 'index'])->name('user.index');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('test-company', [CompanyController::class, 'index']);

Route::get('/whoami', function () {
    return [
        'id' => Auth::id(),
        'user' => Auth::user()?->email,
        'guard' => Auth::getDefaultDriver(),
        'session_id' => session()->getId(),
        'session_all' => session()->all(),
        'auth_key' => session()->get(Auth::guard('web')->getName()),
    ];
})->middleware('web');
