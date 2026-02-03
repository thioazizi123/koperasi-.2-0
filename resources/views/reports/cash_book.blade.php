@extends('layouts.app')
@section('title', 'Buku Kas')

@push('styles')
    <style>
        @media print {

            .welcome-section,
            .filter-section,
            .no-print,
            .sidebar,
            .top-bar {
                display: none !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
                width: 100% !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 10pt !important;
            }

            th,
            td {
                padding: 0.5rem !important;
                border: 1px solid #000 !important;
            }
        }

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
@endpush

@section('content')
    <div class="welcome-section no-print">
        <h1>Buku Kas</h1>
        <p>Pantau arus kas masuk, keluar, dan posisi keuangan koperasi secara real-time.</p>
    </div>

    {{-- Filter Form --}}
    <div class="filter-section no-print"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow); margin-top: 2rem;">
        <form method="GET" action="{{ route('reports.cash_book') }}"
            style="display: flex; gap: 1rem; align-items: end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label
                    style="display: block; margin-bottom: 0.5rem; color: white; font-weight: 600; font-size: 0.875rem;">Kode
                    Transaksi</label>
                <select name="code" onchange="this.form.submit()"
                    style="width: 100%; padding: 0.75rem; border-radius: 0.75rem; border: none; font-size: 0.875rem; background: white; cursor: pointer;">
                    <option value="">Semua Kode</option>
                    <option value="001" {{ request('code') == '001' ? 'selected' : '' }}>001 - Simpanan Pokok</option>
                    <option value="002" {{ request('code') == '002' ? 'selected' : '' }}>002 - Simpanan Wajib</option>
                    <option value="003" {{ request('code') == '003' ? 'selected' : '' }}>003 - Simpanan Operasional</option>
                    <option value="004" {{ request('code') == '004' ? 'selected' : '' }}>004 - Pembiayaan</option>
                    <option value="005" {{ request('code') == '005' ? 'selected' : '' }}>005 - Angsuran</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label
                    style="display: block; margin-bottom: 0.5rem; color: white; font-weight: 600; font-size: 0.875rem;">Tahun</label>
                <select name="year" onchange="this.form.submit()"
                    style="width: 100%; padding: 0.75rem; border-radius: 0.75rem; border: none; font-size: 0.875rem; background: white; cursor: pointer;">
                    <option value="">Semua Tahun</option>
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- Buku Kas Report --}}
    <div class="buku-kas-section" style="margin-top: 2.5rem;">
        <!-- Tabel Buku Kas -->
        <div class="report-table"
            style="background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow); margin-bottom: 2rem; overflow-x: auto;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; font-weight: 700; text-transform: uppercase;">
                    Buku Kas
                    @if($codeFilter == '001')
                        - Simpanan Pokok
                    @elseif($codeFilter == '002')
                        - Simpanan Wajib
                    @elseif($codeFilter == '003')
                        - Simpanan Operasional
                    @elseif($codeFilter == '004')
                        - Pembiayaan
                    @elseif($codeFilter == '005')
                        - Angsuran
                    @else
                        Uang Masuk dan Uang Keluar
                    @endif
                </h2>
                <h3 style="font-size: 1.125rem; font-weight: 600; color: #64748b;">
                    @if($yearFilter)
                        Tahun {{ $yearFilter }}
                    @else
                        Semua Tahun
                    @endif
                </h3>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                    <tr style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <th rowspan="2" style="padding: 1rem; border: 1px solid #e2e8f0; text-align: center; width: 50px;">
                            NO</th>
                        <th rowspan="2" style="padding: 1rem; border: 1px solid #e2e8f0; text-align: center; width: 80px;">
                            KODE</th>
                        <th rowspan="2" style="padding: 1rem; border: 1px solid #e2e8f0; text-align: center; width: 120px;">
                            TANGGAL</th>
                        <th rowspan="2" style="padding: 1rem; border: 1px solid #e2e8f0; text-align: center;">URAIAN</th>
                        <th colspan="2" style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center;">UANG</th>
                        <th rowspan="2" style="padding: 1rem; border: 1px solid #e2e8f0; text-align: center; width: 150px;">
                            SALDO</th>
                    </tr>
                    <tr style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <th style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center; width: 120px;">MASUK
                        </th>
                        <th style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center; width: 120px;">KELUAR
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @forelse($reportData as $row)
                        <tr style="border: 1px solid #e2e8f0;">
                            <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center;">{{ $no++ }}</td>
                            <td
                                style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center; font-family: monospace; font-weight: 700; color: #667eea;">
                                {{ $row['code'] ?? '-' }}
                            </td>
                            <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center;">
                                {{ \Carbon\Carbon::parse($row['date'])->format('d/m/Y') }}</td>
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
                            <td colspan="7"
                                style="padding: 2rem; text-align: center; color: #64748b; border: 1px solid #e2e8f0;">Belum ada
                                transaksi yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="no-print" style="text-align: right; margin-top: 1rem;">
<<<<<<< HEAD
            <button onclick="window.print()" class="print-button"
                style="position: static; padding: 0.75rem 1.5rem; border-radius: 0.75rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
=======
            <button onclick="window.print()" class="print-button" style="position: static; padding: 0.75rem 1.5rem; border-radius: 0.75rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
>>>>>>> 4313f322010693c43f711d7d26a578055bca6953
                Print Laporan
            </button>
        </div>
    </div>
@endsection