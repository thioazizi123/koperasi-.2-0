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

    // Fetch latest transactions (7 days)
    $sevenDaysAgo = now()->subDays(7);

    $latestSavings = \App\Models\Saving::with('member')
        ->where('transaction_date', '>=', $sevenDaysAgo)
        ->latest()
        ->get()
        ->map(function ($item) {
            $typeLabels = [
                'pokok' => 'Simpanan Pokok',
                'wajib' => 'Simpanan Wajib',
                'operasional' => 'Dana Operasional',
            ];
            $item->transaction_type = $typeLabels[$item->type] ?? 'Simpanan';
            $item->display_amount = $item->amount;
            $item->display_member = $item->member ? ($item->member->name . ' - ' . $item->member->member_no) : 'N/A';
            $item->display_status = 'Selesai';
            $item->display_date = \Carbon\Carbon::parse($item->transaction_date)->format('d/m/Y');
            $item->status_color = '#dcfce7';
            $item->text_color = '#166534';
            $item->sort_date = $item->transaction_date;
            return $item;
        });

    $latestFinancings = \App\Models\Financing::with('member')
        ->where('date', '>=', $sevenDaysAgo)
        ->latest()
        ->get()
        ->map(function ($item) {
            $item->transaction_type = 'Pembiayaan';
            $item->display_amount = $item->amount;
            $item->display_member = $item->member ? ($item->member->name . ' - ' . $item->member->member_no) : 'N/A';
            $item->display_status = $item->status;
            $item->display_date = \Carbon\Carbon::parse($item->date)->format('d/m/Y');

            $colors = [
                'Pending' => ['bg' => '#fef9c3', 'text' => '#854d0e'],
                'Disetujui' => ['bg' => '#dcfce7', 'text' => '#166534'],
                'Ditolak' => ['bg' => '#fee2e2', 'text' => '#991b1b'],
                'Lunas' => ['bg' => '#dcfce7', 'text' => '#166534'],
            ];

            $statusColors = $colors[$item->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569'];
            $item->status_color = $statusColors['bg'];
            $item->text_color = $statusColors['text'];
            $item->sort_date = $item->date;
            return $item;
        });

    $latestTransactions = $latestSavings->concat($latestFinancings)->sortByDesc('sort_date');

    return view('home', compact('totalMembers', 'newMembers', 'totalPokok', 'totalWajib', 'totalOperasional', 'totalFinancing', 'latestTransactions'));
});

Route::resource('members', MemberController::class);
Route::resource('financings', FinancingController::class);
Route::put('/installments/{installment}/pay', [FinancingController::class, 'payInstallment'])->name('installments.pay');

Route::get('/report', [ReportController::class, 'index'])->name('reports.index');

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
