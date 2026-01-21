@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="welcome-section">
    <h1>Dashboard Koperasi</h1>
    <p>Selamat datang di sistem manajemen koperasi modern. Kelola data anggota, transaksi simpanan, dan pembiayaan dengan mudah dan transparan.</p>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Anggota</h3>
            <div class="value">1,234</div>
        </div>
        <div class="stat-card">
            <h3>Total Simpanan</h3>
            <div class="value">Rp 150.000.000</div>
        </div>
        <div class="stat-card">
            <h3>Total Pembiayaan</h3>
            <div class="value">Rp 85.500.000</div>
        </div>
        <div class="stat-card">
            <h3>Pendaftar Baru</h3>
            <div class="value">+12</div>
        </div>
    </div>
</div>

<div style="margin-top: 2.5rem; display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div style="background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Transaksi Terakhir</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1rem 0;">Anggota</th>
                    <th style="padding: 1rem 0;">Tipe</th>
                    <th style="padding: 1rem 0;">Jumlah</th>
                    <th style="padding: 1rem 0;">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1rem 0;">Ahmad Fauzi</td>
                    <td style="padding: 1rem 0;">Simpanan</td>
                    <td style="padding: 1rem 0;">Rp 500.000</td>
                    <td style="padding: 1rem 0;"><span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.875rem;">Selesai</span></td>
                </tr>
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1rem 0;">Siti Aminah</td>
                    <td style="padding: 1rem 0;">Pembiayaan</td>
                    <td style="padding: 1rem 0;">Rp 5.000.000</td>
                    <td style="padding: 1rem 0;"><span style="background: #fef9c3; color: #854d0e; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.875rem;">Pending</span></td>
                </tr>
                <tr>
                    <td style="padding: 1rem 0;">Budi Santoso</td>
                    <td style="padding: 1rem 0;">Simpanan</td>
                    <td style="padding: 1rem 0;">Rp 250.000</td>
                    <td style="padding: 1rem 0;"><span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.875rem;">Selesai</span></td>
                </tr>
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
            <p style="font-size: 0.875rem; color: #64748b;">Mulai bulan depan akan ada penyesuaian perhitungan bagi hasil.</p>
        </div>
    </div>
</div>
@endsection
