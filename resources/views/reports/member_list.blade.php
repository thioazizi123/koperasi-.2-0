@extends('layouts.app')
@section('title', 'Daftar Anggota')

@section('styles')
    <style>
        .print-button {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(102, 126, 234, 0.6);
        }
    </style>
@endsection

@section('content')
    <div class="welcome-section no-print">
        <h1>Daftar Anggota Koperasi</h1>
        <p>Laporan data seluruh anggota koperasi yang terdaftar.</p>
    </div>

    <div class="report-table card"
        style="padding: 1.5rem; overflow-x: auto; background: white; border-radius: 1rem; box-shadow: var(--shadow); margin-top: 2rem;">

        <h2 style="text-align:center; font-weight:700; margin-bottom:0.5rem; color: #1e293b;">DAFTAR ANGGOTA KOPERASI</h2>
        <h3 style="text-align:center; font-size:1rem; margin-bottom:2rem; color: #64748b;">PER
            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </h3>

        <table class="table" style="width:100%; border-collapse:collapse; font-size:0.85rem;">
            <thead>
                <tr style="background:#f8fafc; text-align: left; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding:0.75rem; color: #475569; font-weight: 600; width: 40px;">NO</th>
                    <th style="padding:0.75rem; color: #475569; font-weight: 600;">ID</th>
                    <th style="padding:0.75rem; color: #475569; font-weight: 600;">NIK</th>
                    <th style="padding:0.75rem; color: #475569; font-weight: 600;">NAMA</th>
                    <th style="padding:0.75rem; color: #475569; font-weight: 600;">UMUR</th>
                    <th style="padding:0.75rem; color: #475569; font-weight: 600;">JK</th>
                    <th style="padding:0.75rem; color: #475569; font-weight: 600;">PEKERJAAN</th>
                    <th style="padding:0.75rem; color: #475569; font-weight: 600;">ALAMAT</th>
                    <th style="padding:0.75rem; color: #475569; font-weight: 600;">TGL MASUK</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $idx => $member)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding:0.75rem; color: #64748b;">{{ $idx + 1 }}</td>
                        <td
                            style="padding:0.75rem; font-family: monospace; font-weight: 600; color: #1e293b; white-space: nowrap;">
                            {{ $member->member_no ?? '-' }}
                        </td>
                        <td style="padding:0.75rem; font-family: monospace; color: #475569;">{{ $member->nik ?? '-' }}</td>
                        <td style="padding:0.75rem; font-weight: 500; color: #1e293b;">{{ $member->name }}</td>
                        <td style="padding:0.75rem; color: #475569;">{{ $member->age }} Thn</td>
                        <td style="padding:0.75rem; color: #475569;">{{ $member->gender == 'Laki-laki' ? 'L' : 'P' }}</td>
                        <td style="padding:0.75rem; color: #475569;">{{ $member->occupation ?? '-' }}</td>
                        <td style="padding:0.75rem; color: #475569; max-width: 200px; font-size: 0.8rem;">{{ $member->address }}
                        </td>
                        <td style="padding:0.75rem; color: #475569; white-space: nowrap;">
                            {{ $member->join_date->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding: 2rem; text-align: center; color: #94a3b8;">Belum ada data anggota.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="no-print" style="text-align: right; margin-top: 2rem;">
            <button onclick="window.print()" class="print-button"
                style="position: static; padding: 0.8rem 2rem; border-radius: 1rem;">
                <i class="fas fa-print"></i> Print Laporan
            </button>
        </div>
    </div>
@endsection