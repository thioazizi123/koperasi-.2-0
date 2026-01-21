@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="welcome-section">
        <h1>Laporan Keuangan</h1>
        <p>Pantau arus kas masuk, keluar, dan posisi keuangan koperasi secara real-time.</p>
    </div>

    <div style="margin-top: 2.5rem;">
        <!-- Tabel Buku Kas -->
        <div
            style="background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow); margin-bottom: 2rem;">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-book-open" style="color: var(--primary); margin-right: 0.5rem;"></i>
                Buku Kas (Masuk & Keluar) Bulanan
            </h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                        <th style="padding: 1rem;">Bulan</th>
                        <th style="padding: 1rem;">Kas Masuk</th>
                        <th style="padding: 1rem;">Kas Keluar</th>
                        <th style="padding: 1rem;">Saldo / Surplus</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cashBook as $item)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 1rem; font-weight: 500;">{{ $item['month'] }}</td>
                            <td style="padding: 1rem; color: #166534;">Rp {{ number_format($item['income'], 0, ',', '.') }}</td>
                            <td style="padding: 1rem; color: #991b1b;">Rp {{ number_format($item['expense'], 0, ',', '.') }}
                            </td>
                            <td style="padding: 1rem; font-weight: 600;">Rp {{ number_format($item['balance'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Section DLL / Informasi Lainnya -->
        <div style="background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
            <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">
                <i class="fas fa-info-circle" style="color: var(--primary); margin-right: 0.5rem;"></i>
                Lain-lain (Piutang & Kewajiban)
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                @foreach($extraInfo as $info)
                    <div style="border: 1px solid #f1f5f9; padding: 1.5rem; border-radius: 1rem;">
                        <p style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.5rem;">{{ $info['label'] }}</p>
                        <p style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">{{ $info['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection