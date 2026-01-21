@extends('layouts.app')

@section('title', 'Pembiayaan')

@section('content')
    <div class="content-header"
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Daftar Pembiayaan</h1>
        </div>
        <a href="{{ route('financings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Pembiayaan
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success"
            style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="background: white; border-radius: 1rem; box-shadow: var(--shadow); overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left;">
                        <th style="padding: 1rem 1.5rem; font-weight: 600; color: #475569; width: 30px;"></th>
                        <th style="padding: 1rem 1.5rem; font-weight: 600; color: #475569;">Anggota</th>
                        <th style="padding: 1rem 1.5rem; font-weight: 600; color: #475569;">Tipe</th>
                        <th style="padding: 1rem 1.5rem; font-weight: 600; color: #475569;">Jumlah</th>
                        <th style="padding: 1rem 1.5rem; font-weight: 600; color: #475569;">Tenor</th>
                        <th style="padding: 1rem 1.5rem; font-weight: 600; color: #475569;">Tanggal</th>
                        <th style="padding: 1rem 1.5rem; font-weight: 600; color: #475569;">Status</th>
                        <th style="padding: 1rem 1.5rem; font-weight: 600; color: #475569; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($financings as $financing)
                        <tr style="border-bottom: 1px solid #f1f5f9;" class="financing-row {{ $financing->status === 'Disetujui' && $financing->installments->isNotEmpty() ? 'expandable' : '' }}" data-financing-id="{{ $financing->id }}">
                            <td style="padding: 1rem 1.5rem;">
                                @if($financing->status === 'Disetujui' && $financing->installments->isNotEmpty())
                                    <i class="fas fa-chevron-right expand-icon" style="cursor: pointer; color: #64748b; transition: transform 0.2s;"></i>
                                @endif
                            </td>
                            <td style="padding: 1rem 1.5rem; font-weight: 500;">
                                {{ $financing->member->name ?? 'Deleted Member' }}
                            </td>
                            <td style="padding: 1rem 1.5rem;">{{ $financing->type }}</td>
                            <td style="padding: 1rem 1.5rem;">Rp {{ number_format($financing->amount, 0, ',', '.') }}</td>
                            <td style="padding: 1rem 1.5rem;">{{ $financing->duration }} Bulan</td>
                            <td style="padding: 1rem 1.5rem;">{{ $financing->date->format('d/m/Y') }}</td>
                            <td style="padding: 1rem 1.5rem;">
                                @php
                                    $statusColor = match ($financing->status) {
                                        'Disetujui' => '#dcfce7',
                                        'Lunas' => '#dbeafe',
                                        'Ditolak' => '#fee2e2',
                                        default => '#fef9c3',
                                    };
                                    $textColor = match ($financing->status) {
                                        'Disetujui' => '#166534',
                                        'Lunas' => '#1e40af',
                                        'Ditolak' => '#991b1b',
                                        default => '#854d0e',
                                    };
                                @endphp
                                <span style="background: {{ $statusColor }}; color: {{ $textColor }}; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.875rem;">
                                    {{ $financing->status }}
                                </span>
                            </td>
                            <td style="padding: 1rem 1.5rem; text-align: right;">
                                @if($financing->status == 'Pending')
                                    <form action="{{ route('financings.update', $financing->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Disetujui">
                                        <button type="submit" class="btn" style="background:none; border:none; color: #166534; cursor:pointer;" title="Setujui">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('financings.update', $financing->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="Ditolak">
                                        <button type="submit" class="btn" style="background:none; border:none; color: #ef4444; cursor:pointer;" title="Tolak">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('financings.destroy', $financing->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="background:none; border:none; color: #ef4444; cursor:pointer;" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @if($financing->status === 'Disetujui' && $financing->installments->isNotEmpty())
                            <tr class="installment-row" data-financing-id="{{ $financing->id }}" style="display: none; background: #f8fafc;">
                                <td colspan="8" style="padding: 0;">
                                    <div style="padding: 1.5rem; background: #f8fafc;">
                                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #475569; margin-bottom: 1rem;">Jadwal Angsuran</h4>
                                        <table style="width: 100%; font-size: 0.875rem;">
                                            <thead>
                                                <tr style="border-bottom: 1px solid #e2e8f0;">
                                                    <th style="padding: 0.5rem; text-align: left; color: #64748b;">Angsuran</th>
                                                    <th style="padding: 0.5rem; text-align: left; color: #64748b;">Jumlah</th>
                                                    <th style="padding: 0.5rem; text-align: left; color: #64748b;">Jatuh Tempo</th>
                                                    <th style="padding: 0.5rem; text-align: left; color: #64748b;">Status</th>
                                                    <th style="padding: 0.5rem; text-align: left; color: #64748b;">Tanggal Bayar</th>
                                                    <th style="padding: 0.5rem; text-align: right; color: #64748b;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($financing->installments as $installment)
                                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                                        <td style="padding: 0.5rem;">Angsuran ke-{{ $installment->installment_number }}</td>
                                                        <td style="padding: 0.5rem;">Rp {{ number_format($installment->amount, 0, ',', '.') }}</td>
                                                        <td style="padding: 0.5rem;">{{ $installment->due_date->format('d/m/Y') }}</td>
                                                        <td style="padding: 0.5rem;">
                                                            @if($installment->is_paid)
                                                                <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem;">Lunas</span>
                                                            @else
                                                                <span style="background: #fef9c3; color: #854d0e; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem;">Belum Lunas</span>
                                                            @endif
                                                        </td>
                                                        <td style="padding: 0.5rem;">{{ $installment->paid_date ? $installment->paid_date->format('d/m/Y') : '-' }}</td>
                                                        <td style="padding: 0.5rem; text-align: right;">
                                                            @if(!$installment->is_paid)
                                                                <form action="{{ route('installments.pay', $installment->id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="submit" class="btn" style="background:none; border:none; color: #166534; cursor:pointer; font-size: 0.75rem;" title="Tandai Lunas">
                                                                        <i class="fas fa-check-circle"></i> Bayar
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    @if($financings->isEmpty())
                        <tr>
                            <td colspan="8" style="padding: 2rem; text-align: center; color: #94a3b8;">Belum ada data pembiayaan.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.expandable').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.closest('form') || e.target.closest('button')) return;
            
            const financingId = this.dataset.financingId;
            const installmentRow = document.querySelector(`.installment-row[data-financing-id="${financingId}"]`);
            const icon = this.querySelector('.expand-icon');
            
            if (installmentRow.style.display === 'none') {
                installmentRow.style.display = 'table-row';
                icon.style.transform = 'rotate(90deg)';
            } else {
                installmentRow.style.display = 'none';
                icon.style.transform = 'rotate(0deg)';
            }
        });
    });
</script>
@endsection