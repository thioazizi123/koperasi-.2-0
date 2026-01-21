@extends('layouts.app')

@section('title', 'Dana Operasional')

@section('content')
<div class="welcome-section">
    <h1>Dana Operasional</h1>
    <p>Halaman ini menampilkan data penggunaan dan alokasi dana operasional koperasi untuk mendukung kegiatan rutin dan administratif.</p>
</div>

<div style="margin-top: 2.5rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Laporan Dana Operasional</h2>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                <th style="padding: 1rem 0;">Keterangan</th>
                <th style="padding: 1rem 0;">Kategori</th>
                <th style="padding: 1rem 0;">Jumlah</th>
                <th style="padding: 1rem 0;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="4" style="padding: 2rem; text-align: center; color: #64748b;">Belum ada data laporan.</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
