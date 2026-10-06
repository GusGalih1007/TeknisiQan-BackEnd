<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\TicketController;
use App\Http\Controllers\Web\UnitController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('non-user-reports.create');
});

// Halaman Pelaporan Kerusakan Publik (Tanpa Login)
Route::get('/lapor', [ReportController::class, 'create'])->name('non-user-reports.create');
Route::post('/lapor', [ReportController::class, 'store'])->name('non-user-reports.store');

// Halaman Lacak Tiket (Publik)
Route::get('/track', [TicketController::class, 'search'])->name('tickets.search');

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/temp-dashboard', [DashboardController::class, 'index'])->name('temp.dashboard');

    // QR Code Print Preview harus didaftarkan sebelum route resource /units/{unit}
    Route::post('units/bulk-print-preview', [UnitController::class, 'bulkPrintPreview'])->name('units.bulk-print-preview');
    Route::get('units/{id}/print-preview', [UnitController::class, 'printPreview'])->name('units.print-preview');
    Route::resource('units', UnitController::class);
    Route::resource('users', UserController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('rooms', RoomController::class);

    // Company check leader endpoint
    Route::post('companies/check-leader', [CompanyController::class, 'checkLeader'])->name('companies.check-leader');

    // Report history and actions
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('reports/{id}/reject', [ReportController::class, 'rejectReport'])->name('reports.reject');
});