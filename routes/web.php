<?php

use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('home');
});

Route::get('/report', [ReportController::class, 'index'])->name('reports.index');