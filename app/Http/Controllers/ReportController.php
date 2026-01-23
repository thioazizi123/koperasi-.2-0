<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $transactions = collect();

        // 1. Get Savings (Deposits and Withdrawals)
        $savings = \App\Models\Saving::with('member')->get()->map(function ($s) {
            return [
                'date' => $s->transaction_date,
                'description' => ($s->amount > 0 ? 'Setoran ' : 'Penarikan ') . 
                                ($s->type == 'pokok' ? 'Simpanan Pokok' : ($s->type == 'wajib' ? 'Simpanan Wajib' : 'Dana Operasional')) . 
                                ' - ' . ($s->member->name ?? 'N/A'),
                'in' => $s->amount > 0 ? $s->amount : 0,
                'out' => $s->amount < 0 ? abs($s->amount) : 0,
            ];
        });
        $transactions = $transactions->concat($savings);

        // 2. Get Financing Disbursed (Outflows)
        $financings = \App\Models\Financing::with('member')->where('status', 'Disetujui')->get()->map(function ($f) {
            return [
                'date' => $f->date,
                'description' => 'Pencairan Pembiayaan (' . $f->type . ') - ' . ($f->member->name ?? 'N/A'),
                'in' => 0,
                'out' => $f->amount,
            ];
        });
        $transactions = $transactions->concat($financings);

        // 3. Get Installments Paid (Inflows)
        $installments = \App\Models\Installment::with('financing.member')->where('is_paid', true)->get()->map(function ($i) {
            return [
                'date' => $i->paid_date,
                'description' => 'Angsuran ke-' . $i->installment_number . ' (' . ($i->financing->type ?? 'N/A') . ') - ' . ($i->financing->member->name ?? 'N/A'),
                'in' => $i->amount,
                'out' => 0,
            ];
        });
        $transactions = $transactions->concat($installments);

        // Sort by date and calculate running balance
        $sortedTransactions = $transactions->sortBy('date')->values();
        
        $balance = 0;
        $reportData = $sortedTransactions->map(function ($t) use (&$balance) {
            $balance += ($t['in'] - $t['out']);
            $t['balance'] = $balance;
            return $t;
        });

        return view('reports.index', compact('reportData'));
    }
}
