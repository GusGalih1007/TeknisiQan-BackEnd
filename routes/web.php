<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CompanyController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\UnitController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('non-user-reports.create');
});

// Halaman Pelaporan Kerusakan Publik (Tanpa Login)
Route::get('/lapor', [ReportController::class, 'create'])->name('non-user-reports.create');
Route::post('/lapor', [ReportController::class, 'store'])->name('non-user-reports.store');

// Halaman Lacak Tiket (Publik)
Route::get('/track', [\App\Http\Controllers\Web\TicketController::class, 'search'])->name('tickets.search');

Route::prefix('auth')->group(function () {
    Route::get('login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/temp-dashboard', [DashboardController::class, 'index'])->name('temp.dashboard');

Route::middleware('auth')->group(function () {

    // QR Code Print Preview harus didaftarkan sebelum route resource /units/{unit}
    Route::post('units/bulk-print-preview', [UnitController::class, 'bulkPrintPreview'])->name('units.bulk-print-preview');
    Route::get('units/{id}/print-preview', [UnitController::class, 'printPreview'])->name('units.print-preview');
    Route::resource('units', UnitController::class);
    Route::resource('users', UserController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('rooms', RoomController::class);

    // Report history and actions
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::post('reports/{id}/reject', [ReportController::class, 'rejectReport'])->name('reports.reject');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Route::get('/whoami', function () {
//     return [
//         'id' => Auth::id(),
//         'user' => Auth::user()?->email,
//         'guard' => Auth::getDefaultDriver(),
//         'session_id' => session()->getId(),
//         'session_all' => session()->all(),
//         'auth_key' => session()->get(Auth::guard('web')->getName()),
//     ];
// })->middleware('web');
