<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\FinancingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SavingController;
use App\Models\Member;

Route::get('/', function () {
    $totalMembers = Member::count();
    $newMembersRaw = Member::whereDate('join_date', '>=', now()->subDays(7))->count();
    $newMembers = '+' . $newMembersRaw;
    $latestMembers = Member::latest()->take(5)->get();
    return view('home', compact('totalMembers', 'newMembers', 'latestMembers'));
});

Route::resource('members', MemberController::class);
Route::resource('financings', FinancingController::class);
Route::put('/installments/{installment}/pay', [FinancingController::class, 'payInstallment'])->name('installments.pay');

Route::get('/report', [ReportController::class, 'index'])->name('reports.index');

Route::prefix('simpanan')->group(function () {
    Route::get('/wajib', [SavingController::class, 'wajib'])->name('simpanan.wajib');
    Route::get('/pokok', [SavingController::class, 'pokok'])->name('simpanan.pokok');
    Route::get('/operasional', [SavingController::class, 'operasional'])->name('simpanan.operasional');
});
