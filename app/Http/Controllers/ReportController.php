<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // --- 1. Original Report Data (Buku Kas) ---
        $transactions = collect();

        // Get Savings (Deposits and Withdrawals)
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

        // Get Financing Disbursed (Outflows)
        $financings = \App\Models\Financing::with('member')->where('status', 'Disetujui')->get()->map(function ($f) {
            return [
                'date' => $f->date,
                'description' => 'Pencairan Pembiayaan (' . $f->type . ') - ' . ($f->member->name ?? 'N/A'),
                'in' => 0,
                'out' => $f->amount,
            ];
        });
        $transactions = $transactions->concat($financings);

        // Get Installments Paid (Inflows)
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


        // --- 2. Matrix Report Data (Daftar Simpanan Anggota) ---
        // Fetch raw savings again if needed, or reuse logic
        $allSavings = \App\Models\Saving::all();

        // Get distinct years from savings
        $years = $allSavings->pluck('transaction_date')->map(fn($d) => $d->year)->unique()->sort()->values();
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        // Get Members sorted by name
        $members = \App\Models\Member::orderBy('name')->get();

        // Process Data into Matrix
        $matrix = [];
        foreach ($members as $member) {
            $row = [
                'name' => $member->name,
                'pokok' => $member->savings->where('type', 'pokok')->sum('amount'), // Pokok is lifetime
                'years' => [],
                'total_all' => 0
            ];

            $row['total_all'] += $row['pokok'];

            foreach ($years as $year) {
                // Filter savings for this member and year
                $yearSavings = $member->savings->filter(function ($s) use ($year) {
                    return $s->transaction_date->year == $year;
                });

                $wajib = $yearSavings->where('type', 'wajib')->sum('amount');
                $operasional = $yearSavings->where('type', 'operasional')->sum('amount');

                $total_year = $wajib + $operasional;

                $row['years'][$year] = [
                    'wajib' => $wajib,
                    'operasional' => $operasional,
                    'total' => $total_year
                ];

                $row['total_all'] += $total_year;
            }
            $matrix[] = $row;
        }

        return view('reports.index', compact('reportData', 'matrix', 'years'));
    }
}
