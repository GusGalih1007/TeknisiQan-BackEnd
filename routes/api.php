<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\UnitController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('company', [CompanyController::class, 'index']);
Route::post('company/store', [CompanyController::class, 'store']);

// Unit API routes
Route::get('units/{id}', [UnitController::class, 'show']);
Route::get('companies/{compId}/units', [UnitController::class, 'unitsByCompany']);
