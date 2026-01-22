@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="welcome-section">
        <h1>Dashboard Koperasi</h1>
        <p>Selamat datang di sistem manajemen koperasi modern. Kelola data anggota, transaksi simpanan, dan pembiayaan
            dengan mudah dan transparan.</p>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Anggota</h3>
                <div class="value">{{ number_format($totalMembers) }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #10b981;">
                <h3>Total Simpanan Pokok</h3>
                <div class="value">Rp {{ number_format($totalPokok, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #3b82f6;">
                <h3>Total Simpanan Wajib</h3>
                <div class="value">Rp {{ number_format($totalWajib, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card" style="border-left-color: #f59e0b;">
                <h3>Total Dana Operasional</h3>
                <div class="value">Rp {{ number_format($totalOperasional, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <h3>Total Pembiayaan</h3>
                <div class="value">Rp {{ number_format($totalFinancing, 0, ',', '.') }}</div>
            </div>
            <div class="stat-card">
                <h3>Pendaftar Baru <small style="font-size: 0.7em; font-weight: normal;">(7 Hari)</small></h3>
                <div class="value">{{ $newMembers }}</div>
            </div>
        </div>
    </div>

    <div style="margin-top: 2.5rem; display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <div style="background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Transaksi Terakhir</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 1rem 0;">Tanggal</th>
                        <th style="padding: 1rem 0;">Anggota</th>
                        <th style="padding: 1rem 0;">Tipe</th>
                        <th style="padding: 1rem 0;">Jumlah</th>
                        <th style="padding: 1rem 0;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestTransactions as $transaction)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 1rem 0; color: #64748b; font-size: 0.875rem;">{{ $transaction->display_date }}</td>
                            <td style="padding: 1rem 0;">{{ $transaction->display_member }}</td>
                            <td style="padding: 1rem 0;">{{ $transaction->transaction_type }}</td>
                            <td style="padding: 1rem 0;">Rp {{ number_format($transaction->display_amount, 0, ',', '.') }}</td>
                            <td style="padding: 1rem 0;">
                                <span
                                    style="background: {{ $transaction->status_color }}; color: {{ $transaction->text_color }}; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.875rem;">
                                    {{ $transaction->display_status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 2rem 0; text-align: center; color: #64748b;">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                    <span style="font-size: 1.5rem;">📅</span>
                                    <p>Tidak ada transaksi dalam 7 hari terakhir.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Pengumuman</h2>
            <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                <p style="font-weight: 600; font-size: 0.95rem;">Rapat Tahunan Anggota</p>
                <p style="font-size: 0.875rem; color: #64748b;">Akan dilaksanakan pada tanggal 15 Februari 2026.</p>
            </div>
            <div>
                <p style="font-weight: 600; font-size: 0.95rem;">Kebijakan Bagi Hasil Baru</p>
                <p style="font-size: 0.875rem; color: #64748b;">Mulai bulan depan akan ada penyesuaian perhitungan bagi
                    hasil.</p>
            </div>
        </div>
    </div>
@endsection