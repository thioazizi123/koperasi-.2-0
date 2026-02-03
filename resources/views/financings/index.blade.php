@extends('layouts.app')

@section('title', 'Pembiayaan')

@section('styles')
<style>
    /* Custom Modal CSS */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 2000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .confirm-modal {
        background: white;
        padding: 2.5rem;
        border-radius: 1.5rem;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        text-align: center;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .modal-overlay.active .confirm-modal {
        transform: scale(1);
    }

    .modal-icon {
        width: 70px;
        height: 70px;
        background: #fef9c3;
        color: #854d0e;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem;
    }

    .modal-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: center;
    }

    .btn-modal {
        padding: 0.8rem 2rem;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all 0.2s;
        min-width: 120px;
    }

    .btn-cancel { background: #f1f5f9; color: #475569; }
    .btn-cancel:hover { background: #e2e8f0; }
    .btn-confirm { background: #4f46e5; color: white; }
    .btn-confirm:hover { background: #4338ca; transform: translateY(-2px); }
</style>
@endsection

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
                        <th style="padding: 1rem 1.5rem; font-weight: 600; color: #475569;">ID</th>
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
                            <td style="padding: 1rem 1.5rem; font-family: monospace; font-weight: 600; color: #64748b;">
                                {{ $financing->member->member_no ?? '-' }}
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
                                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
                                            <div>
                                                <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Angsuran</h4>
                                                @php
                                                    $totalCount = $financing->installments->count();
                                                    $paidCount = $financing->installments->where('is_paid', true)->count();
                                                    $remainingCount = $totalCount - $paidCount;
                                                    $remainingAmount = $financing->installments->where('is_paid', false)->sum('amount');
                                                @endphp
                                                <div style="display: flex; gap: 1.5rem; font-size: 0.875rem; color: #64748b;">
                                                    <div>Jumlah Angsuran: <span class="summary-paid-count" style="color: #1e293b; font-weight: 600;">{{ $paidCount }}/{{ $totalCount }} Bulan</span></div>
                                                    <div>Sisa Angsuran: <span class="summary-remaining-count" style="color: #1e293b; font-weight: 600;">{{ $remainingCount }} Bulan</span></div>
                                                    <div>Sisa Tagihan: <span class="summary-remaining-amount" style="color: #e11d48; font-weight: 600;">Rp {{ number_format($remainingAmount, 0, ',', '.') }}</span></div>
                                                </div>
                                            </div>
                                            <button onclick="printInstallments({{ $financing->id }})" class="btn" style="background: #f1f5f9; color: #475569; padding: 0.5rem 1rem; font-size: 0.875rem;">
                                                <i class="fas fa-print"></i> Cetak Angsuran
                                            </button>
                                        </div>
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
                                                                <form action="{{ route('installments.pay', $installment->id) }}" method="POST" class="payment-form" style="display:inline;">
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

    <!-- Custom Confirmation Modal -->
    <div id="paymentModal" class="modal-overlay">
        <div class="confirm-modal">
            <div class="modal-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Konfirmasi Bayar</h3>
            <p style="color: #64748b; font-size: 0.875rem;">Apakah Anda yakin ingin melakukan pembayaran angsuran ini?</p>
            <div class="modal-buttons">
                <button type="button" class="btn-modal btn-cancel" onclick="closeModal()">Batal</button>
                <button type="button" id="confirmBtn" class="btn-modal btn-confirm">Ya</button>
            </div>
        </div>
    </div>

    <!-- Print Confirmation Modal -->
    <div id="printModal" class="modal-overlay">
        <div class="confirm-modal">
            <div class="modal-icon" style="background: #dbeafe; color: #1e40af;">
                <i class="fas fa-print"></i>
            </div>
            <h3 id="printModalTitle" style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Cetak Daftar Angsuran</h3>
            <p style="color: #64748b; font-size: 0.875rem;">Apakah Anda ingin mencetak daftar angsuran?</p>
            <div class="modal-buttons">
                <button type="button" class="btn-modal btn-cancel" onclick="closePrintModal()">Batal</button>
                <button type="button" id="confirmPrintBtn" class="btn-modal btn-confirm">Ya</button>
            </div>
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

    // Handle AJAX Payment
    let currentForm = null;
    let currentFinancingIdForPrint = null;

    function showModal(form) {
        currentForm = form;
        document.getElementById('paymentModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('paymentModal').classList.remove('active');
        currentForm = null;
    }

    function showPrintModal(financingId) {
        currentFinancingIdForPrint = financingId;
        document.getElementById('printModal').classList.add('active');
    }

    function closePrintModal() {
        document.getElementById('printModal').classList.remove('active');
        currentFinancingIdForPrint = null;
    }

    document.getElementById('confirmPrintBtn').addEventListener('click', function() {
        if (currentFinancingIdForPrint) {
            printInstallments(currentFinancingIdForPrint);
            closePrintModal();
        }
    });

    document.getElementById('confirmBtn').addEventListener('click', function() {
        if (!currentForm) return;
        
        const form = currentForm;
        const url = form.action;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        closeModal();
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>...';

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Update UI status
                const row = form.closest('tr');
                const statusCell = row.querySelector('td:nth-child(4)');
                const dateCell = row.querySelector('td:nth-child(5)');
                const actionCell = row.querySelector('td:nth-child(6)');

                statusCell.innerHTML = '<span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-size: 0.75rem;">Lunas</span>';
                dateCell.innerText = result.data.paid_date;
                actionCell.innerHTML = '<span style="color: #64748b; font-size: 0.75rem;"><i class="fas fa-check"></i> Terbayar</span>';

                // Update summary values
                const financingId = result.data.installment.financing_id;
                const installmentRow = document.querySelector(`.installment-row[data-financing-id="${financingId}"]`);
                
                if (installmentRow) {
                    installmentRow.querySelector('.summary-paid-count').innerText = result.data.summary.paid_count + '/' + result.data.summary.total_count + ' Bulan';
                    installmentRow.querySelector('.summary-remaining-count').innerText = result.data.summary.remaining_count + ' Bulan';
                    installmentRow.querySelector('.summary-remaining-amount').innerText = 'Rp ' + result.data.summary.remaining_amount;
                }

                // Show Print Modal instead of native confirm
                showPrintModal(financingId);
            } else {
                alert('Gagal memproses pembayaran: ' + result.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Bayar';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan jaringan.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check-circle"></i> Bayar';
        });
    });

    document.querySelectorAll('.payment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            showModal(this);
        });
    });

    // printReceipt is no longer needed as we use printInstallments (full table view)
    function printReceipt(data) {
        printInstallments(data.installment.financing_id);
    }

    function printInstallments(id) {
        const row = document.querySelector(`.installment-row[data-financing-id="${id}"]`);
        const content = row.innerHTML;
        const memberNo = row.previousElementSibling.querySelector('td:nth-child(2)').innerText;
        const memberName = row.previousElementSibling.querySelector('td:nth-child(3)').innerText;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Angsuran - ' + memberName + '</title>');
        printWindow.document.write('<style>body { font-family: sans-serif; padding: 40px; } table { width: 100%; border-collapse: collapse; margin-top: 20px; } th, td { border: 1px solid #ddd; padding: 12px; text-align: left; } th { background-color: #f8fafc; } h2 { margin-bottom: 5px; } .no-print { display: none; } button, form { display: none; } th:last-child, td:last-child { display: none; } .status-lunas { color: #166534; font-weight: bold; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h2>Angsuran Koperasi</h2>');
        printWindow.document.write('<p>Anggota: <strong>' + memberName + ' (' + memberNo.trim() + ')</strong></p>');
        printWindow.document.write('<hr>');
        printWindow.document.write(content);
        printWindow.document.write('<script>window.print(); setTimeout(function() { window.close(); }, 500);<\/script>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();
    }
</script>
@endsection