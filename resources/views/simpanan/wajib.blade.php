@extends('layouts.app')

@section('title', 'Simpanan Wajib')

@section('content')
<div class="welcome-section">
    <h1>Simpanan Wajib</h1>
    <p>Halaman ini digunakan untuk mengelola data simpanan wajib anggota koperasi. Simpanan wajib adalah jumlah simpanan tertentu yang harus dibayarkan oleh anggota dalam waktu dan kesempatan tertentu.</p>
</div>

<div style="margin-top: 2.5rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Daftar Setoran Simpanan Wajib</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                <th style="padding: 1rem 0;">Nama Anggota</th>
                <th style="padding: 1rem 0;">Bulan/Tahun</th>
                <th style="padding: 1rem 0;">Jumlah</th>
                <th style="padding: 1rem 0;">Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" style="padding: 2rem; text-align: center; color: #64748b;">Belum ada data transaksi.</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
