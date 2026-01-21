<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $cashBook = [
            ['month' => 'Januari 2026', 'income' => 25000000, 'expense' => 15000000, 'balance' => 10000000],
            ['month' => 'Februari 2026', 'income' => 30000000, 'expense' => 12000000, 'balance' => 18000000],
            ['month' => 'Maret 2026', 'income' => 28000000, 'expense' => 20000000, 'balance' => 8000000],
        ];

        $extraInfo = [
            ['label' => 'Total Piutang Anggota', 'value' => 'Rp 45.000.000'],
            ['label' => 'Total Hutang Pihak Ketiga', 'value' => 'Rp 5.000.000'],
            ['label' => 'Cadangan Dana Sosial', 'value' => 'Rp 12.500.000'],
        ];

        return view('reports.index', compact('cashBook', 'extraInfo'));
    }
}
