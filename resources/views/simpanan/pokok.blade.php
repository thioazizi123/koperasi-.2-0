@extends('layouts.app')

@section('title', 'Simpanan Pokok')

@section('content')
    <div class="welcome-section">
        <h1>Simpanan Pokok</h1>
        <p>Halaman ini digunakan untuk mengelola data simpanan pokok anggota koperasi. Simpanan pokok adalah sejumlah uang
            yang sama banyaknya yang wajib dibayarkan oleh anggota pada saat masuk menjadi anggota.</p>
    </div>

    <div style="margin-top: 2.5rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Daftar Setoran Simpanan Pokok</h2>

        @if(session('success'))
            <div style="padding: 1rem; background: #dcfce7; color: #166534; border-radius: 0.5rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1rem;">
                {{ session('error') }}
            </div>
        @endif

        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1rem 0;">ID</th>
                    <th style="padding: 1rem 0;">Nama Anggota</th>
                    <th style="padding: 1rem 0;">Status</th>
                    <th style="padding: 1rem 0; text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    @php
                        $isPaid = $member->savings->count() > 0;
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem 0; font-family: monospace; font-weight: 600; color: #64748b;">
                            {{ $member->member_no ?? '-' }}
                        </td>
                        <td style="padding: 1rem 0;">{{ $member->name }}</td>
                        <td style="padding: 1rem 0;">
                            @if($isPaid)
                                <span
                                    style="background: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.875rem;">Lunas</span>
                            @else
                                <span
                                    style="background: #fee2e2; color: #991b1b; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.875rem;">Belum
                                    Lunas</span>
                            @endif
                        </td>
                        <td style="padding: 1rem 0; text-align: right;">
                            @if(!$isPaid)
                                <form action="{{ route('simpanan.pokok.store') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                                    <button type="submit"
                                        style="background: var(--primary-color); color: white; padding: 0.5rem 1rem; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.875rem;">
                                        Bayar Rp 50.000
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('simpanan.pokok.destroy', $member->id) }}" method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan status lunas ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="background: #f1f5f9; color: #64748b; padding: 0.5rem 1rem; border: none; border-radius: 0.5rem; cursor: pointer; font-size: 0.875rem;">
                                        Batalkan
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="padding: 2rem; text-align: center; color: #64748b;">Belum ada data anggota.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="font-weight: bold; background: #f8fafc;">
                    <td style="padding: 1rem; border-top: 2px solid #e2e8f0;">TOTAL TERKUMPUL</td>
                    <td colspan="2"
                        style="padding: 1rem; text-align: right; border-top: 2px solid #e2e8f0; font-size: 1.125rem; color: var(--primary-color);">
                        Rp {{ number_format($totalPaid, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection