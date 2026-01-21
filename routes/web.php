<?php

<<<<<<< HEAD
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FinancingController;
use App\Models\Member;
=======
use App\Http\Controllers\ReportController;
<<<<<<< HEAD
use App\Http\Controllers\SavingController;
=======
>>>>>>> af4bf4565d9417ba6bf8a1095aa9d3cc061bfd9a
>>>>>>> f8dae23d7020d9d7b2de6bf36f5a6fa5b7bdef04

Route::get('/', function () {
    $totalMembers = Member::count();
    $newMembersRaw = Member::whereDate('join_date', '>=', now()->subDays(7))->count();
    $newMembers = '+' . $newMembersRaw;
    $latestMembers = Member::latest()->take(5)->get();
    return view('home', compact('totalMembers', 'newMembers', 'latestMembers'));
});

<<<<<<< HEAD
Route::get('/report', [ReportController::class, 'index'])->name('reports.index');
Route::prefix('simpanan')->group(function () {
    Route::get('/wajib', [SavingController::class, 'wajib'])->name('simpanan.wajib');
    Route::get('/pokok', [SavingController::class, 'pokok'])->name('simpanan.pokok');
    Route::get('/operasional', [SavingController::class, 'operasional'])->name('simpanan.operasional');
});
=======
<<<<<<< HEAD
Route::resource('members', MemberController::class);
Route::resource('financings', FinancingController::class);
Route::put('/installments/{installment}/pay', [FinancingController::class, 'payInstallment'])->name('installments.pay');
=======
Route::get('/report', [ReportController::class, 'index'])->name('reports.index');
>>>>>>> af4bf4565d9417ba6bf8a1095aa9d3cc061bfd9a
>>>>>>> f8dae23d7020d9d7b2de6bf36f5a6fa5b7bdef04
