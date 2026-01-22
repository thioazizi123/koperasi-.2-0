<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Member;
use App\Models\Saving;

class SavingController extends Controller
{
    public function wajib()
    {
        return view('simpanan.wajib');
    }

    public function pokok()
    {
        $members = Member::with(['savings' => function($query) {
            $query->where('type', 'pokok');
        }])->get();

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

    public function operasional()
    {
        return view('simpanan.operasional');
    }
}
