<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function cashBook(Request $request)
    {
        // Get filter parameters
        $codeFilter = $request->get('code'); // 001, 002, 003, 004, or null
        $yearFilter = $request->get('year'); // 2024, 2025, etc. or null

        // --- 1. Filtered Report Data (Buku Kas) ---
        $transactions = collect();

        // Get Savings (Deposits and Withdrawals) - only if code is 001/002/003 or no filter
        if (!$codeFilter || in_array($codeFilter, ['001', '002', '003'])) {
            $savingsQuery = \App\Models\Saving::with('member');

            // Apply code filter for savings
            if ($codeFilter && in_array($codeFilter, ['001', '002', '003'])) {
                $type = $this->getTypeFromCode($codeFilter);
                if ($type) {
                    $savingsQuery->where('type', $type);
                }
            }

            // Apply year filter for savings
            if ($yearFilter) {
                $savingsQuery->whereRaw("strftime('%Y', transaction_date) = ?", [$yearFilter]);
            }

            $savings = $savingsQuery->get()->map(function ($s) {
                return [
                    'date' => $s->transaction_date,
                    'code' => $this->getCodeFromType($s->type),
                    'description' => ($s->amount > 0 ? 'Setoran ' : 'Penarikan ') .
                        ($s->type == 'pokok' ? 'Simpanan Pokok' : ($s->type == 'wajib' ? 'Simpanan Wajib' : 'Dana Operasional')) .
                        ' - ' . ($s->member ? ($s->member->name . ' (' . $s->member->member_no . ')') : 'N/A'),
                    'in' => $s->amount > 0 ? $s->amount : 0,
                    'out' => $s->amount < 0 ? abs($s->amount) : 0,
                ];
            });
            $transactions = $transactions->concat($savings);
        }

        // Get Financing Disbursed (Outflows) - only if code is 004 or no filter
        if (!$codeFilter || $codeFilter == '004') {
            $financingsQuery = \App\Models\Financing::with('member')->where('status', 'Disetujui');

            // Apply year filter
            if ($yearFilter) {
                $financingsQuery->whereRaw("strftime('%Y', date) = ?", [$yearFilter]);
            }

            $financings = $financingsQuery->get()->map(function ($f) {
                return [
                    'date' => $f->date,
                    'code' => '004',
                    'description' => 'Pencairan Pembiayaan (' . $f->type . ') - ' . ($f->member ? ($f->member->name . ' (' . $f->member->member_no . ')') : 'N/A'),
                    'in' => 0,
                    'out' => $f->amount,
                ];
            });
            $transactions = $transactions->concat($financings);
        }

        // Get Installments Paid (Inflows) - show when no filter or code 005
        if (!$codeFilter || $codeFilter == '005') {
            $installmentsQuery = \App\Models\Installment::with('financing.member')->where('is_paid', true);

            // Apply year filter
            if ($yearFilter) {
                $installmentsQuery->whereRaw("strftime('%Y', paid_date) = ?", [$yearFilter]);
            }

            $installments = $installmentsQuery->get()->map(function ($i) {
                return [
                    'date' => $i->paid_date,
                    'code' => '005', // Angsuran code 005
                    'description' => 'Angsuran ke-' . $i->installment_number . ' (' . ($i->financing->type ?? 'N/A') . ') - ' . ($i->financing->member ? ($i->financing->member->name . ' (' . $i->financing->member->member_no . ')') : 'N/A'),
                    'in' => $i->amount,
                    'out' => 0,
                ];
            });
            $transactions = $transactions->concat($installments);
        }

        // Sort by date and calculate running balance
        $sortedTransactions = $transactions->sortBy('date')->values();

        $balance = 0;
        $reportData = $sortedTransactions->map(function ($t) use (&$balance) {
            $balance += ($t['in'] - $t['out']);
            $t['balance'] = $balance;
            return $t;
        });


        // --- 2. Get Available Years for Filter ---
        $availableYears = collect();

        // Get years from savings
        $savingYears = \App\Models\Saving::selectRaw("strftime('%Y', transaction_date) as year")
            ->distinct()
            ->pluck('year');
        $availableYears = $availableYears->concat($savingYears);

        // Get years from financings
        $financingYears = \App\Models\Financing::selectRaw("strftime('%Y', date) as year")
            ->distinct()
            ->pluck('year');
        $availableYears = $availableYears->concat($financingYears);

        // Get years from installments
        $installmentYears = \App\Models\Installment::where('is_paid', true)
            ->selectRaw("strftime('%Y', paid_date) as year")
            ->distinct()
            ->pluck('year');
        $availableYears = $availableYears->concat($installmentYears);

        $availableYears = $availableYears->unique()->sort()->values();
        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        return view('reports.cash_book', compact('reportData', 'availableYears', 'codeFilter', 'yearFilter'));
    }

    public function members(Request $request)
    {
        // --- Matrix Report Data (Daftar Simpanan Anggota) ---
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
                'member_no' => $member->member_no,
                'pokok' => $member->savings->where('type', 'pokok')->sum('amount'),
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

        return view('reports.members', compact('matrix', 'years'));
    }


    /**
     * Get code from savings/financing type
     */
    private function getCodeFromType($type)
    {
        return match ($type) {
            'wajib' => '001',
            'pokok' => '002',
            'operasional' => '003',
            default => null
        };
    }

    /**
     * Get type from code
     */
    private function getTypeFromCode($code)
    {
        return match ($code) {
            '001' => 'wajib',
            '002' => 'pokok',
            '003' => 'operasional',
            default => null
        };
    }
}
