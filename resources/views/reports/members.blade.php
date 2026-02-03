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
        <h1>Daftar Simpanan Anggota</h1>
        <p>Laporan rekapan simpanan seluruh anggota koperasi.</p>
    </div>

    <div class="report-table card matrix-report-section"
        style="padding: 1.5rem; overflow-x: auto; background: white; border-radius: 1rem; box-shadow: var(--shadow); margin-top: 2rem;">

        <h2 style="text-align:center; font-weight:700; margin-bottom:0.5rem; color: #1e293b;">DAFTAR SIMPANAN ANGGOTA
            KOPERASI</h2>
        <h3 style="text-align:center; font-size:1rem; margin-bottom:2rem; color: #64748b;">PER
            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</h3>

        <table class="table" style="width:100%; border-collapse:collapse; font-size:0.8rem; text-align:center;">
            <thead>
                <tr style="background:#e2e8f0;">
                    <th rowspan="2" style="border:1px solid #94a3b8; padding:0.25rem; vertical-align: middle;">NO</th>
                    <th rowspan="2"
                        style="border:1px solid #94a3b8; padding:0.25rem; vertical-align: middle;">ID</th>
                    <th rowspan="2"
                        style="border:1px solid #94a3b8; padding:0.25rem; vertical-align: middle;">NIK</th>
                    <th rowspan="2"
                        style="border:1px solid #94a3b8; padding:0.25rem; vertical-align: middle;">Nama</th>
                    <th rowspan="2" style="border:1px solid #94a3b8; padding:0.25rem; vertical-align: middle;">
                        Simpanan<br>Pokok</th>
                    @foreach($years as $year)
                        <th colspan="3" style="border:1px solid #94a3b8; padding:0.125rem;">{{ $year }}</th>
                    @endforeach
                    <th rowspan="2" style="border:1px solid #94a3b8; padding:0.25rem; vertical-align: middle;">
                        Jumlah<br>Simpanan<br>Anggota</th>
                </tr>
                <tr style="background:#f1f5f9;">
                    @foreach($years as $year)
                        <th style="border:1px solid #94a3b8; padding:0.25rem;">Simpanan<br>Wajib</th>
                        <th style="border:1px solid #94a3b8; padding:0.125rem;">Dana<br>Operasional</th>
                        <th style="border:1px solid #94a3b8; padding:0.125rem; font-weight:bold;">Total<br>{{ $year }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($matrix as $idx => $row)
                    <tr>
                        <td style="border:1px solid #cbd5e1; padding:0.25rem;">{{ $idx + 1 }}</td>
                        <td style="border:1px solid #cbd5e1; padding:0.25rem; font-family: monospace; font-size: 0.75rem;">
                            {{ $row['member_no'] ?? '-' }}</td>
                        <td style="border:1px solid #cbd5e1; padding:0.25rem; font-family: monospace; font-size: 0.75rem;">{{ $row['nik'] ?? '-' }}
                        </td>
                        <td style="border:1px solid #cbd5e1; padding:0.25rem; text-align:left;">{{ $row['name'] }}</td>
                        <td style="border:1px solid #cbd5e1; padding:0.25rem; text-align:right;">
                            {{ number_format($row['pokok'], 0, ',', '.') }}</td>
                        @foreach($years as $year)
                            <td style="border:1px solid #cbd5e1; padding:0.25rem; text-align:right;">
                                {{ number_format($row['years'][$year]['wajib'], 0, ',', '.') }}</td>
                            <td style="border:1px solid #cbd5e1; padding:0.25rem; text-align:right;">
                                {{ number_format($row['years'][$year]['operasional'], 0, ',', '.') }}</td>
                            <td
                                style="border:1px solid #cbd5e1; padding:0.25rem; text-align:right; font-weight:bold; background:#f8fafc;">
                                {{ number_format($row['years'][$year]['total'], 0, ',', '.') }}</td>
                        @endforeach
                        <td style="border:1px solid #cbd5e1; padding:0.25rem; text-align:right; font-weight:bold;">
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
        <div class="no-print" style="text-align: right; margin-top: 2rem;">
            <button onclick="window.print()" class="print-button"
                style="position: static; padding: 0.8rem 2rem; border-radius: 1rem;">
                <i class="fas fa-print"></i> Print Laporan
            </button>
        </div>
    </div>
@endsection