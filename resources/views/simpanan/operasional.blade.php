@extends('layouts.app')

@section('title', 'Dana Operasional')

@section('content')
    <div class="welcome-section">
        <h1>Dana Operasional</h1>
        <p>Halaman ini menampilkan data penggunaan dan alokasi dana operasional koperasi untuk mendukung kegiatan rutin dan
            administratif.</p>
    </div>

    <div style="margin-top: 2rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Input Dana Operasional</h2>
        
        @if(session('success'))
            <div style="padding: 1rem; background: #dcfce7; color: #166534; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <ul style="margin: 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('simpanan.operasional.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: end;">
            @csrf
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">Pilih Anggota (Cari Nama)</label>
                <input list="member_list" name="member_name" id="member_input" placeholder="Ketik nama anggota..." required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                <datalist id="member_list">
                    @foreach($members as $member)
                        <option value="{{ $member->name }}" data-id="{{ $member->id }}">
                    @endforeach
                </datalist>
                <input type="hidden" name="member_id" id="member_id_hidden">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">Jumlah (Min. Rp 30.000)</label>
                <input type="number" name="amount" placeholder="Masukkan jumlah..." min="30000" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
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

    <div style="margin-top: 2.5rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow); overflow-x: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem;">Laporan Dana Operasional Tahun {{ $year }}</h2>
            <form action="{{ route('simpanan.operasional') }}" method="GET" style="display: flex; gap: 0.5rem; align-items: center;">
                <label style="font-size: 0.875rem; color: #64748b;">Filter Tahun:</label>
                <select name="year" onchange="this.form.submit()" style="padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </form>
        </div>
        <table style="width: 100%; border-collapse: collapse; min-width: 1200px; font-size: 0.875rem;">
            <thead>
                <tr style="text-align: center; border-bottom: 2px solid #f1f5f9; background: #f8fafc;">
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">No</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0; text-align: left;">Nama</th>
                    <th style="padding: 0.75rem; border: 1px solid #e2e8f0;">Saldo {{ $year - 1 }}</th>
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

@section('scripts')
    <script>
        document.getElementById('member_input').addEventListener('input', function(e) {
            var input = e.target;
            var list = document.getElementById('member_list');
            var hiddenInput = document.getElementById('member_id_hidden');
            var inputValue = input.value;

            hiddenInput.value = "";

            for (var i = 0; i < list.options.length; i++) {
                var option = list.options[i];
                if (option.value === inputValue) {
                    hiddenInput.value = option.getAttribute('data-id');
                    break;
                }
            }
        });

        // Check on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            if (e.target.action.includes('store')) {
                var hiddenInput = document.getElementById('member_id_hidden');
                if (!hiddenInput.value) {
                    e.preventDefault();
                    alert('Mohon pilih anggota yang valid dari daftar.');
                }
            }
        });
    </script>
@endsection
@endsection