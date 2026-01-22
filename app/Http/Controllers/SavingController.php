<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;
use App\Models\Saving;

class SavingController extends Controller
{
    public function wajib(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $type = 'wajib';
        $members = Member::with([
            'savings' => function ($q) use ($type) {
                $q->where('type', $type);
            }
        ])->orderBy('name')->get();

        foreach ($members as $member) {
            $member->saldo_awal = $member->savings->where('transaction_date', '<', "$year-01-01")->sum('amount');
            $monthly = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthly[$m] = $member->savings->filter(function ($s) use ($year, $m) {
                    return $s->transaction_date->year == $year && $s->transaction_date->month == $m;
                })->sum('amount');
            }
            $member->monthly_savings = $monthly;
            $member->total_year = $member->saldo_awal + array_sum($monthly);
        }

        return view('simpanan.wajib', compact('members', 'year'));
    }

    public function storeWajib(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:20000',
            'transaction_date' => 'required|date',
        ], [
            'amount.min' => 'Minimal setoran simpanan wajib adalah Rp 20.000'
        ]);

        Saving::create([
            'member_id' => $validated['member_id'],
            'type' => 'wajib',
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
        ]);

        return back()->with('success', 'Setoran simpanan wajib berhasil ditambahkan.');
    }

    public function pokok()
    {
        $members = Member::with([
            'savings' => function ($query) {
                $query->where('type', 'pokok');
            }
        ])->get();

        $totalPaid = Saving::where('type', 'pokok')->sum('amount');

        return view('simpanan.pokok', compact('members', 'totalPaid'));
    }

    public function storePokok(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id'
        ]);

        // Check if already paid
        $exists = Saving::where('member_id', $validated['member_id'])
            ->where('type', 'pokok')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anggota sudah membayar simpanan pokok.');
        }

        Saving::create([
            'member_id' => $validated['member_id'],
            'type' => 'pokok',
            'amount' => 50000,
            'transaction_date' => now(),
        ]);

        return back()->with('success', 'Pembayaran simpanan pokok berhasil.');
    }

    public function destroyPokok(Member $member)
    {
        Saving::where('member_id', $member->id)
            ->where('type', 'pokok')
            ->delete();

        return back()->with('success', 'Status simpanan pokok berhasil dikembalikan ke Belum Lunas.');
    }

    public function operasional(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $type = 'operasional';
        $members = Member::with([
            'savings' => function ($q) use ($type) {
                $q->where('type', $type);
            }
        ])->orderBy('name')->get();

        foreach ($members as $member) {
            $member->saldo_awal = $member->savings->where('transaction_date', '<', "$year-01-01")->sum('amount');
            $monthly = [];
            for ($m = 1; $m <= 12; $m++) {
                $monthly[$m] = $member->savings->filter(function ($s) use ($year, $m) {
                    return $s->transaction_date->year == $year && $s->transaction_date->month == $m;
                })->sum('amount');
            }
            $member->monthly_savings = $monthly;
            $member->total_year = $member->saldo_awal + array_sum($monthly);
        }

        return view('simpanan.operasional', compact('members', 'year'));
    }

    public function storeOperasional(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:30000',
            'transaction_date' => 'required|date',
        ], [
            'amount.min' => 'Minimal setoran dana operasional adalah Rp 30.000'
        ]);

        Saving::create([
            'member_id' => $validated['member_id'],
            'type' => 'operasional',
            'amount' => $validated['amount'],
            'transaction_date' => $validated['transaction_date'],
        ]);

        return back()->with('success', 'Dana operasional berhasil ditambahkan.');
    }
}
