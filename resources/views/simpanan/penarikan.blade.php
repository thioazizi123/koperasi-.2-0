@extends('layouts.app')

@section('title', 'Penarikan Simpanan')

@section('content')
    <div class="welcome-section">
        <h1>Penarikan Simpanan</h1>
        <p>Halaman ini digunakan untuk mencatat pengambilan kembali uang dari simpanan wajib, simpanan pokok, maupun dana operasional oleh anggota.</p>
    </div>

    <div style="margin-top: 2rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow);">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Input Penarikan Simpanan</h2>
        
        @if(session('success'))
            <div style="padding: 1rem; background: #dcfce7; color: #166534; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                {{ session('error') }}
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

        <form action="{{ route('simpanan.penarikan.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: end;">
            @csrf
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">Pilih Anggota</label>
                <input list="member_list" name="member_name" id="member_input" placeholder="Ketik nama anggota..." required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                <datalist id="member_list">
                    @foreach($members as $member)
                        <option value="{{ $member->name }}" data-id="{{ $member->id }}">
                    @endforeach
                </datalist>
                <input type="hidden" name="member_id" id="member_id_hidden">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">Jenis Simpanan</label>
                <select name="type" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
                    <option value="pokok">Simpanan Pokok</option>
                    <option value="wajib" selected>Simpanan Wajib</option>
                    <option value="operasional">Dana Operasional</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">Jumlah Penarikan</label>
                <input type="number" name="amount" placeholder="0" min="1" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem; color: #64748b;">Tanggal Penarikan</label>
                <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
            </div>
            <div>
                <button type="submit" style="width: 100%; background: #ef4444; color: white; padding: 0.75rem; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 500;">
                    Proses Penarikan
                </button>
            </div>
        </form>
    </div>

    <div style="margin-top: 2.5rem; background: white; padding: 2rem; border-radius: 1.5rem; box-shadow: var(--shadow); overflow-x: auto;">
        <h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Riwayat Penarikan Terakhir</h2>
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 2px solid #f1f5f9; background: #f8fafc;">
                    <th style="padding: 1rem;">No</th>
                    <th style="padding: 1rem;">Tanggal</th>
                    <th style="padding: 1rem;">Anggota</th>
                    <th style="padding: 1rem;">Jenis</th>
                    <th style="padding: 1rem; text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $index => $w)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem;">{{ $index + 1 }}</td>
                        <td style="padding: 1rem;">{{ $w->transaction_date->format('d/m/Y') }}</td>
                        <td style="padding: 1rem; font-weight: 500;">{{ $w->member->name }}</td>
                        <td style="padding: 1rem;">
                            {{ $w->type == 'pokok' ? 'Simpanan Pokok' : ($w->type == 'wajib' ? 'Simpanan Wajib' : 'Dana Operasional') }}
                        </td>
                        <td style="padding: 1rem; text-align: right; color: #ef4444; font-weight: 600;">
                            {{ number_format(abs($w->amount), 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #64748b;">Belum ada riwayat penarikan.</td>
                    </tr>
                @endforelse
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

        document.querySelector('form').addEventListener('submit', function(e) {
            var hiddenInput = document.getElementById('member_id_hidden');
            if (!hiddenInput.value) {
                e.preventDefault();
                alert('Mohon pilih anggota yang valid dari daftar.');
            }
        });
    </script>
@endsection
@endsection
