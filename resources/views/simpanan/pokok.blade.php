@extends('layouts.app')

@section('title', 'Simpanan Pokok')

@section('content')
<div class="welcome-section">
    <h1>Simpanan Pokok</h1>
    <p>Halaman ini digunakan untuk mengelola data simpanan pokok anggota koperasi. Simpanan pokok adalah sejumlah uang yang sama banyaknya yang wajib dibayarkan oleh anggota pada saat masuk menjadi anggota.</p>
</div>

<div style="margin-top: 2.5rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Daftar Setoran Simpanan Pokok</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                <th style="padding: 1rem 0;">Nama Anggota</th>
                <th style="padding: 1rem 0;">ID Anggota</th>
                <th style="padding: 1rem 0;">Jumlah</th>
                <th style="padding: 1rem 0;">Status</th>
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
