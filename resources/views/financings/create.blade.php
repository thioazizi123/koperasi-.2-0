@extends('layouts.app')

@section('title', 'Tambah Pembiayaan')

@section('content')
    <div class="content-header" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">Pengajuan Pembiayaan Baru
        </h1>
    </div>

    <div class="card"
        style="background: white; border-radius: 1rem; box-shadow: var(--shadow); padding: 2rem; max-width: 800px; margin: 0 auto;">
        <form action="{{ route('financings.store') }}" method="POST">
            @csrf

            <div class="form-group" style="position: relative;">
                <label class="form-label"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #334155;">Pilih Anggota</label>
                <input type="text" id="memberSearch" placeholder="Ketik Nama Anggota..." autocomplete="off" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem; font-family: inherit; font-size: 1rem;">
                <input type="hidden" name="member_id" id="selectedMemberId" required>

                <!-- Custom dropdown -->
                <div id="memberDropdown"
                    style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #cbd5e1; border-radius: 0.5rem; margin-top: 0.25rem; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000;">
                </div>

                <small style="color: #64748b; font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                    Contoh: Ahmad Fauzi
                </small>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label class="form-label"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #334155;">Tipe Pembiayaan</label>
                <input type="text" name="type" class="form-control" placeholder="Ketik Tipe Pembiayaan..." required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem;">
            </div>

            <div class="form-group" style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label class="form-label"
                        style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #334155;">Jumlah Pembiayaan
                        (Rp)</label>
                    <input type="text" name="amount" class="form-control" required pattern="[0-9]+" inputmode="numeric"
                        style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem;">
                </div>
                <div>
                    <label class="form-label"
                        style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #334155;">Tenor
                        (Bulan)</label>
                    <input type="text" name="duration" class="form-control" required pattern="[0-9]+" inputmode="numeric"
                        value="12" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem;">
                </div>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label class="form-label"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #334155;">Tanggal
                    Pengajuan</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem;">
            </div>

            <div style="text-align: right; margin-top: 2.5rem;">
                <a href="{{ route('financings.index') }}" class="btn"
                    style="background: #e2e8f0; color: #334155; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-block; margin-right: 0.5rem;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary"
                    style="background: #e11d48; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 500; cursor: pointer;">
                    Ajukan Pembiayaan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        const memberSearch = document.getElementById('memberSearch');
        const selectedMemberId = document.getElementById('selectedMemberId');
        const memberDropdown = document.getElementById('memberDropdown');
        const members = @json($members->map(fn($m) => ['id' => $m->id, 'name' => $m->name]));

        // Show dropdown with filtered results
        memberSearch.addEventListener('input', function () {
            const searchValue = this.value.trim();

            // Clear hidden field
            selectedMemberId.value = '';

            // Hide dropdown if empty
            if (!searchValue) {
                memberDropdown.style.display = 'none';
                return;
            }

            // Filter members by Name
            const filtered = members.filter(m => {
                const searchLower = searchValue.toLowerCase();
                return m.name && m.name.toLowerCase().includes(searchLower);
            });

            if (filtered.length === 0) {
                memberDropdown.style.display = 'none';
                return;
            }

            // Build dropdown HTML
            let html = '';
            filtered.forEach(member => {
                html += `<div class="dropdown-item" data-id="${member.id}" data-name="${member.name}" style="padding: 0.75rem 1rem; cursor: pointer; border-bottom: 1px solid #f1f5f9;">
                                <div style="font-weight: 600; color: #1e293b;">${member.name}</div>
                            </div>`;
            });

            memberDropdown.innerHTML = html;
            memberDropdown.style.display = 'block';

            // Add click handlers
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function () {
                    const memberId = this.dataset.id;
                    const memberName = this.dataset.name;

                    memberSearch.value = memberName;
                    selectedMemberId.value = memberId;
                    memberDropdown.style.display = 'none';
                });

                // Hover effect
                item.addEventListener('mouseenter', function () {
                    this.style.background = '#f8fafc';
                });
                item.addEventListener('mouseleave', function () {
                    this.style.background = 'white';
                });
            });
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!memberSearch.contains(e.target) && !memberDropdown.contains(e.target)) {
                memberDropdown.style.display = 'none';
            }
        });

        // Validate before submit
        document.querySelector('form').addEventListener('submit', function (e) {
            if (!selectedMemberId.value) {
                e.preventDefault();
                alert('Pilih ID anggota yang valid dari daftar');
                memberSearch.focus();
            }
        });
    </script>
@endsection