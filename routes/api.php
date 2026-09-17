<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('company', [CompanyController::class, 'index']);
Route::post('company/store', [CompanyController::class, 'store']);
Route::get('company', [CompanyController::class, 'index']);
Route::get('company', [CompanyController::class, 'index']);
Route::get('company', [CompanyController::class, 'index']);
Route::get('company', [CompanyController::class, 'index']);
