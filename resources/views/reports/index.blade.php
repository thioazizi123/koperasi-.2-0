@extends('layouts.app')
@section('title', 'Laporan Keuangan')

@section('content')
    <div class="welcome-section">
        <h1>Laporan Keuangan</h1>
        <p>Pantau arus kas masuk, keluar, dan posisi keuangan koperasi secara real-time.</p>
    </div>

    {{-- 1. Buku Kas Report --}}
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

    {{-- 2. Matrix Report --}}
    <div class="card"
        style="padding: 1.5rem; overflow-x: auto; background: white; border-radius: 1rem; box-shadow: var(--shadow); margin-top: 3rem;">

        <h2 style="text-align:center; font-weight:700; margin-bottom:0.5rem; color: #1e293b;">DAFTAR SIMPANAN ANGGOTA
            KOPERASI</h2>
        <h3 style="text-align:center; font-size:1rem; margin-bottom:2rem; color: #64748b;">PER
            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</h3>

        <table class="table" style="width:100%; border-collapse:collapse; font-size:0.8rem; text-align:center;">
            <thead>
                <tr style="background:#e2e8f0;">
                    <th rowspan="2" style="border:1px solid #94a3b8; padding:0.5rem; vertical-align: middle;">NO</th>
                    <th rowspan="2"
                        style="border:1px solid #94a3b8; padding:0.5rem; min-width:100px; vertical-align: middle;">ID</th>
                    <th rowspan="2"
                        style="border:1px solid #94a3b8; padding:0.5rem; min-width:150px; vertical-align: middle;">Nama</th>
                    <th rowspan="2" style="border:1px solid #94a3b8; padding:0.5rem; vertical-align: middle;">
                        Simpanan<br>Pokok</th>
                    @foreach($years as $year)
                        <th colspan="3" style="border:1px solid #94a3b8; padding:0.25rem;">{{ $year }}</th>
                    @endforeach
                    <th rowspan="2" style="border:1px solid #94a3b8; padding:0.5rem; vertical-align: middle;">
                        Jumlah<br>Simpanan<br>Anggota</th>
                </tr>
                <tr style="background:#f1f5f9;">
                    @foreach($years as $year)
                        <th style="border:1px solid #94a3b8; padding:0.25rem;">Simpanan<br>Wajib</th>
                        <th style="border:1px solid #94a3b8; padding:0.25rem;">Dana<br>Operasional</th>
                        <th style="border:1px solid #94a3b8; padding:0.25rem; font-weight:bold;">Jumlah<br>Tahun {{ $year }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($matrix as $idx => $row)
                    <tr>
                        <td style="border:1px solid #cbd5e1; padding:0.5rem;">{{ $idx + 1 }}</td>
                        <td style="border:1px solid #cbd5e1; padding:0.5rem; font-family: monospace;">{{ $row['member_no'] ?? '-' }}</td>
                        <td style="border:1px solid #cbd5e1; padding:0.5rem; text-align:left;">{{ $row['name'] }}</td>
                        <td style="border:1px solid #cbd5e1; padding:0.5rem; text-align:right;">
                            {{ number_format($row['pokok'], 0, ',', '.') }}</td>
                        @foreach($years as $year)
                            <td style="border:1px solid #cbd5e1; padding:0.5rem; text-align:right;">
                                {{ number_format($row['years'][$year]['wajib'], 0, ',', '.') }}</td>
                            <td style="border:1px solid #cbd5e1; padding:0.5rem; text-align:right;">
                                {{ number_format($row['years'][$year]['operasional'], 0, ',', '.') }}</td>
                            <td
                                style="border:1px solid #cbd5e1; padding:0.5rem; text-align:right; font-weight:bold; background:#f8fafc;">
                                {{ number_format($row['years'][$year]['total'], 0, ',', '.') }}</td>
                        @endforeach
                        <td style="border:1px solid #cbd5e1; padding:0.5rem; text-align:right; font-weight:bold;">
                            {{ number_format($row['total_all'], 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 5 + ($years->count() * 3) }}"
                            style="padding: 2rem; border: 1px solid #cbd5e1; color: #64748b;">Belum ada data anggota.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection