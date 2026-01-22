@extends('layouts.app')

@section('title', 'Simpanan Wajib')

@section('content')
    <div class="welcome-section">
        <h1>Simpanan Wajib</h1>
        <p>Halaman ini digunakan untuk mengelola data simpanan wajib anggota koperasi. Simpanan wajib adalah jumlah simpanan
            tertentu yang harus dibayarkan oleh anggota dalam waktu dan kesempatan tertentu.</p>
    </div>

    <div style="margin-top: 2rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Input Setoran Simpanan Wajib</h2>
        
        @if(session('success'))
            <div style="padding: 1rem; background: #dcfce7; color: #166534; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('simpanan.wajib.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: end;">
            @csrf
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">Pilih Anggota</label>
                <select name="member_id" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">Jumlah (Rp)</label>
                <input type="number" name="amount" value="20000" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">Tanggal Transaksi</label>
                <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
            </div>
            <div>
                <button type="submit" style="width: 100%; background: var(--primary-color); color: white; padding: 0.75rem; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 500;">
                    Simpan Setoran
                </button>
            </div>
        </form>
    </div>

    <div
        style="margin-top: 2.5rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow); overflow-x: auto;">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Laporan Simpanan Wajib Tahun {{ $year }}</h2>
        <table style="width: 100%; border-collapse: collapse; min-width: 1200px; font-size: 0.875rem;">
            <thead>
                <tr style="text-align: center; border-bottom: 2px solid #f1f5f9; background: #f8fafc;">
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">No</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: left;">Nama</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Saldo {{ $year }}</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Jan</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Feb</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Mar</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Apr</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Mei</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Jun</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Jul</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Agu</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Sep</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Okt</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Nov</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Des</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Jumlah {{ $year }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $index => $member)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: center;">{{ $index + 1 }}</td>
                        <td style="padding: 0.75rem; border: 1px solid #e2e8f0; font-weight: 500;">{{ $member->name }}</td>
                        <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: right;">
                            {{ number_format($member->saldo_awal, 0, ',', '.') }}</td>
                        @foreach($member->monthly_savings as $amount)
                            <td style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: right;">
                                {{ $amount > 0 ? number_format($amount, 0, ',', '.') : '-' }}</td>
                        @endforeach
                        <td
                            style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: right; font-weight: 600; background: #f8fafc;">
                            {{ number_format($member->total_year, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection