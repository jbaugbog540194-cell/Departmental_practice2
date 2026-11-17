<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EquipmentReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'equipment.index' : 'login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::get('/reports', [EquipmentReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [EquipmentReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('/reports/excel', [EquipmentReportController::class, 'excel'])->name('reports.excel');

    Route::resource('equipment', EquipmentController::class);
});
