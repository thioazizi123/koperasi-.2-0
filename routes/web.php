<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\SavingController;

Route::get('/', function () {
    return view('home');
});

Route::get('/report', [ReportController::class, 'index'])->name('reports.index');
Route::prefix('simpanan')->group(function () {
    Route::get('/wajib', [SavingController::class, 'wajib'])->name('simpanan.wajib');
    Route::get('/pokok', [SavingController::class, 'pokok'])->name('simpanan.pokok');
    Route::get('/operasional', [SavingController::class, 'operasional'])->name('simpanan.operasional');
});
