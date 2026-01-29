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

    <div style="margin-top: 2.5rem;">
        <div style="background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Ringkasan Anggota Terbaru</h2>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @forelse($members as $member)
                    <div style="border: 1px solid #e2e8f0; border-radius: 0.75rem; padding: 1.25rem;">
                        <div style="margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
                            <h3 style="font-size: 1rem; font-weight: 600; margin: 0; color: #1e293b;">{{ $member->name }}</h3>
                            <span style="font-size: 0.8rem; color: #64748b;">Bergabung:
                                {{ $member->join_date->format('d/m/Y') }}</span>
                        </div>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="text-align: left; background-color: #f8fafc;">
                                    <th
                                        style="padding: 0.75rem; font-size: 0.875rem; color: #475569; font-weight: 600; border-radius: 0.5rem 0 0 0.5rem;">
                                        Simpanan Pokok</th>
                                    <th style="padding: 0.75rem; font-size: 0.875rem; color: #475569; font-weight: 600;">
                                        Simpanan Wajib</th>
                                    <th
                                        style="padding: 0.75rem; font-size: 0.875rem; color: #475569; font-weight: 600; border-radius: 0 0.5rem 0.5rem 0;">
                                        Pembiayaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding: 0.75rem; color: #334155;">Rp
                                        {{ number_format($member->summary_pokok, 0, ',', '.') }}</td>
                                    <td style="padding: 0.75rem; color: #334155;">Rp
                                        {{ number_format($member->summary_wajib, 0, ',', '.') }}</td>
                                    <td style="padding: 0.75rem; color: #334155;">
                                        @if($member->financing_details->isNotEmpty())
                                            @foreach($member->financing_details as $detail)
                                                <div style="margin-bottom: 0.5rem; border-bottom: 1px dashed #e2e8f0; padding-bottom: 0.5rem; last:border-bottom:none;">
                                                    <div style="font-weight: 500;">Rp {{ number_format($detail['amount'], 0, ',', '.') }}</div>
                                                    <div style="font-size: 0.75rem; color: #64748b; background-color: #f1f5f9; display: inline-block; padding: 0.1rem 0.4rem; border-radius: 0.25rem; margin-top: 0.2rem;">
                                                        Angsuran ke-{{ $detail['progress'] }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            Rp 0
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @empty
                    <div
                        style="padding: 2rem 0; text-align: center; color: #64748b; border: 1px dashed #cbd5e1; border-radius: 0.75rem;">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                            <span style="font-size: 1.5rem;">👥</span>
                            <p>Belum ada data anggota.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection