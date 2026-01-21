<?php

<<<<<<< HEAD
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FinancingController;
use App\Models\Member;
=======
use App\Http\Controllers\ReportController;
>>>>>>> af4bf4565d9417ba6bf8a1095aa9d3cc061bfd9a

Route::get('/', function () {
    $totalMembers = Member::count();
    $newMembersRaw = Member::whereDate('join_date', '>=', now()->subDays(7))->count();
    $newMembers = '+' . $newMembersRaw;
    $latestMembers = Member::latest()->take(5)->get();
    return view('home', compact('totalMembers', 'newMembers', 'latestMembers'));
});

<<<<<<< HEAD
Route::resource('members', MemberController::class);
Route::resource('financings', FinancingController::class);
Route::put('/installments/{installment}/pay', [FinancingController::class, 'payInstallment'])->name('installments.pay');
=======
Route::get('/report', [ReportController::class, 'index'])->name('reports.index');
>>>>>>> af4bf4565d9417ba6bf8a1095aa9d3cc061bfd9a
