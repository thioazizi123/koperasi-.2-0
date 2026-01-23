@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
    <div class="welcome-section">
        <h1>Laporan Keuangan</h1>
        <p>Pantau arus kas masuk, keluar, dan posisi keuangan koperasi secara real-time.</p>
    </div>

    <div style="margin-top: 2.5rem;">
        <!-- Tabel Buku Kas -->
        <div style="background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow); margin-bottom: 2rem; overflow-x: auto;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; text-transform: uppercase;">Buku Kas Uang Masuk dan Uang Keluar</h2>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #64748b;">Tahun {{ date('Y') }}</h3>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <th rowspan="2" style="padding: 1rem; border: 1px solid #e2e8f0; text-align: center; width: 50px;">NO</th>
                        <th rowspan="2" style="padding: 1rem; border: 1px solid #e2e8f0; text-align: center; width: 120px;">TANGGAL</th>
                        <th rowspan="2" style="padding: 1rem; border: 1px solid #e2e8f0; text-align: center;">URAIAN</th>
                        <th colspan="2" style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center;">UANG</th>
                        <th rowspan="2" style="padding: 1rem; border: 1px solid #e2e8f0; text-align: center; width: 150px;">SALDO</th>
                    </tr>
                    <tr style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <th style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center; width: 120px;">MASUK</th>
                        <th style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center; width: 120px;">KELUAR</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($reportData as $row)
                        <tr style="border: 1px solid #e2e8f0;">
                            <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center;">{{ $no++ }}</td>
                            <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center;">{{ \Carbon\Carbon::parse($row['date'])->format('d/m/Y') }}</td>
                            <td style="padding: 0.75rem; border: 1px solid #e2e8f0;">{{ $row['description'] }}</td>
                            <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: right; color: #166534;">
                                {{ $row['in'] > 0 ? number_format($row['in'], 0, ',', '.') : '' }}
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: right; color: #991b1b;">
                                {{ $row['out'] > 0 ? number_format($row['out'], 0, ',', '.') : '' }}
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: right; font-weight: 600;">
                                {{ number_format($row['balance'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: #64748b; border: 1px solid #e2e8f0;">Belum ada transaksi yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection