<?php

namespace App\Http\Controllers;

use App\Models\Financing;
use App\Models\Installment;
use App\Models\Member;
use Illuminate\Http\Request;

class FinancingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $financings = Financing::with(['member', 'installments'])->latest()->get();
        return view('financings.index', compact('financings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        return view('financings.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        $validated['status'] = 'Pending'; // Default status

        Financing::create($validated);

        return redirect()->route('financings.index')->with('success', 'Pengajuan pembiayaan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Financing $financing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Financing $financing)
    {
        // For simplicity, we might handle status updates in index or a separate modal
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Financing $financing)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Disetujui,Ditolak,Lunas',
        ]);

        $oldStatus = $financing->status;
        $financing->update($validated);

        // Auto-generate installments when approved
        if ($validated['status'] === 'Disetujui' && $oldStatus !== 'Disetujui') {
            $this->generateInstallments($financing);
        }

        return redirect()->route('financings.index')->with('success', 'Status pembiayaan diperbarui.');
    }

    /**
     * Generate installments for approved financing
     */
    private function generateInstallments(Financing $financing)
    {
        $installmentAmount = $financing->amount / $financing->duration;
        $startDate = now();

        for ($i = 1; $i <= $financing->duration; $i++) {
            Installment::create([
                'financing_id' => $financing->id,
                'installment_number' => $i,
                'amount' => $installmentAmount,
                'due_date' => $startDate->copy()->addMonths($i),
                'is_paid' => false,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Financing $financing)
    {
        $financing->delete();
        return redirect()->route('financings.index')->with('success', 'Data pembiayaan dihapus.');
    }

    /**
     * Mark an installment as paid
     */
    public function payInstallment(Request $request, Installment $installment)
    {
        $installment->update([
            'is_paid' => true,
            'paid_date' => now(),
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            $financing = $installment->financing->fresh(['installments']);
            $totalCount = $financing->installments->count();
            $paidCount = $financing->installments->where('is_paid', true)->count();
            $remainingCount = $totalCount - $paidCount;
            $remainingAmount = $financing->installments->where('is_paid', false)->sum('amount');

            return response()->json([
                'success' => true,
                'message' => 'Angsuran berhasil ditandai lunas.',
                'data' => [
                    'installment' => $installment,
                    'member' => $financing->member,
                    'paid_date' => \Carbon\Carbon::parse($installment->paid_date)->format('d/m/Y'),
                    'amount' => number_format((float) $installment->amount, 0, ',', '.'),
                    'installment_number' => $installment->installment_number,
                    'summary' => [
                        'paid_count' => $paidCount,
                        'total_count' => $totalCount,
                        'remaining_count' => $remainingCount,
                        'remaining_amount' => number_format((float) $remainingAmount, 0, ',', '.'),
                    ]
                ]
            ]);
        }

        return redirect()->route('financings.index')->with('success', 'Angsuran berhasil ditandai lunas.');
    }
}
