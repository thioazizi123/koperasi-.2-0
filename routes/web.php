<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\FinancingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SavingController;
use App\Models\Member;

Route::get('/', function () {
    $totalMembers = Member::count();
    $newMembersRaw = Member::whereDate('join_date', '>=', now()->subDays(7))->count();
    $newMembers = '+' . $newMembersRaw;

    // Calculate real totals
    $totalPokok = \App\Models\Saving::where('type', 'pokok')->sum('amount');
    $totalWajib = \App\Models\Saving::where('type', 'wajib')->sum('amount');
    $totalOperasional = \App\Models\Saving::where('type', 'operasional')->sum('amount');
    $totalFinancing = \App\Models\Financing::where('status', 'Disetujui')->sum('amount');

    // Fetch members with summary
    $members = \App\Models\Member::with(['savings', 'financings.installments'])
        ->latest()
        ->limit(10)
        ->get()
        ->map(function ($member) {
            $member->summary_pokok = $member->savings->where('type', 'pokok')->sum('amount');
            $member->summary_wajib = $member->savings->where('type', 'wajib')->sum('amount');

            // Filter functionality for detailed display
            $activeFinancings = $member->financings->where('status', 'Disetujui');
            $member->summary_financing = $activeFinancings->sum('amount');

            $member->financing_details = $activeFinancings->map(function ($financing) {
                $paidInstallments = $financing->installments->where('is_paid', true)->count();
                $totalInstallments = $financing->duration; // Assuming duration is in months/installments
    
                return [
                    'amount' => $financing->amount,
                    'progress' => "{$paidInstallments}/{$totalInstallments}",
                    'status' => $financing->status, // Or derive status like 'Lunas' if paid >= total
                ];
            });

            return $member;
        });

    return view('home', compact('totalMembers', 'newMembers', 'totalPokok', 'totalWajib', 'totalOperasional', 'totalFinancing', 'members'));
});

Route::resource('members', MemberController::class);
Route::resource('financings', FinancingController::class);
Route::put('/installments/{installment}/pay', [FinancingController::class, 'payInstallment'])->name('installments.pay');

Route::get('/report', function() {
    return redirect()->route('reports.cash_book');
})->name('reports.index');
Route::get('/report/cash-book', [ReportController::class, 'cashBook'])->name('reports.cash_book');
Route::get('/report/members', [ReportController::class, 'members'])->name('reports.members');


Route::prefix('simpanan')->group(function () {
    Route::get('/wajib', [SavingController::class, 'wajib'])->name('simpanan.wajib');
    Route::post('/wajib', [SavingController::class, 'storeWajib'])->name('simpanan.wajib.store');
    Route::get('/pokok', [SavingController::class, 'pokok'])->name('simpanan.pokok');
    Route::post('/pokok', [SavingController::class, 'storePokok'])->name('simpanan.pokok.store');
    Route::delete('/pokok/{member}', [SavingController::class, 'destroyPokok'])->name('simpanan.pokok.destroy');
    Route::get('/operasional', [SavingController::class, 'operasional'])->name('simpanan.operasional');
    Route::post('/operasional', [SavingController::class, 'storeOperasional'])->name('simpanan.operasional.store');
    Route::get('/penarikan', [SavingController::class, 'penarikan'])->name('simpanan.penarikan');
    Route::post('/penarikan', [SavingController::class, 'storePenarikan'])->name('simpanan.penarikan.store');
});
