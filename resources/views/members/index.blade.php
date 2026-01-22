@extends('layouts.app')

@section('title', 'Keanggotaan')

@section('styles')
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .table-container {
            background: white;
            padding: 2rem;
            border-radius: 1.5rem;
            box-shadow: var(--shadow);
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        .table th {
            font-weight: 600;
            color: #64748b;
            background-color: #f8fafc;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .badge-blue {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-pink {
            background: #fce7f3;
            color: #9d174d;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.show {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            width: 100%;
            max-width: 500px;
            position: relative;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal.show .modal-content {
            transform: translateY(0);
        }

        .close-btn {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 1.5rem;
            cursor: pointer;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #1e293b;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            font-family: inherit;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <div>
            <h1 style="font-size: 2rem; color: #1e1b4b; margin-bottom: 0.5rem;">Data Keanggotaan</h1>
        </div>
        <button onclick="openModal()" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Anggota
        </button>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.75rem; margin-bottom: 2rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID Anggota</th>
                    <th>Nama Lengkap</th>
                    <th>Umur</th>
                    <th>Jenis Kelamin</th>
                    <th>Pekerjaan</th>
                    <th>Alamat</th>
                    <th>Tgl Masuk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($members as $member)
                    <tr>
                        <td>
                            <span
                                style="font-family: monospace; background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem;">
                                {{ $member->member_id ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #1e293b;">{{ $member->name }}</div>
                        </td>
                        <td>{{ $member->age }} Tahun</td>
                        <td>
                            <span class="badge {{ $member->gender == 'Laki-laki' ? 'badge-blue' : 'badge-pink' }}">
                                {{ $member->gender }}
                            </span>
                        </td>
                        <td>{{ $member->occupation }}</td>
                        <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $member->address }}
                        </td>
                        <td>{{ $member->join_date->format('d M Y') }}</td>
                        <td>
                            <button onclick="editMember({{ $member }})" class="btn"
                                style="background:none; border:none; color: #f59e0b; cursor:pointer; margin-right: 0.5rem;"
                                title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('members.destroy', $member->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:none; border:none; color: #ef4444; cursor:pointer;"
                                    title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if($members->isEmpty())
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: #64748b;">
                            <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
                            <p>Belum ada data anggota.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div id="memberModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle" style="margin-bottom: 1.5rem; color: #1e1b4b;">Tambah Anggota Baru</h2>

            <form id="memberForm" action="{{ route('members.store') }}" method="POST">
                @csrf
                <div id="methodField"></div>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" required>
                    </div>
                    <div>
                        <label class="form-label">Jenis Kelamin</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="">Pilih...</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Mata Pencarian</label>
                    <input type="text" id="occupation" name="occupation" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tempat Tinggal</label>
                    <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" id="join_date" name="join_date" class="form-control" value="{{ date('Y-m-d') }}"
                        required>
                </div>

                <div style="text-align: right; margin-top: 2rem;">
                    <button type="button" onclick="closeModal()" class="btn"
                        style="background: #e2e8f0; margin-right: 0.5rem;">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openModal() {
            document.getElementById('memberForm').reset();
            document.getElementById('modalTitle').innerText = 'Tambah Anggota Baru';
            document.getElementById('memberForm').action = "{{ route('members.store') }}";
            document.getElementById('methodField').innerHTML = '';

            const modal = document.getElementById('memberModal');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function editMember(member) {
            document.getElementById('modalTitle').innerText = 'Edit Data Anggota';
            document.getElementById('memberForm').action = `/members/${member.id}`;
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('name').value = member.name;
            // Format date string to YYYY-MM-DD for input type=date
            document.getElementById('date_of_birth').value = new Date(member.date_of_birth).toISOString().split('T')[0];
            document.getElementById('gender').value = member.gender;
            document.getElementById('occupation').value = member.occupation;
            document.getElementById('address').value = member.address;
            document.getElementById('join_date').value = new Date(member.join_date).toISOString().split('T')[0];

            const modal = document.getElementById('memberModal');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('memberModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        window.onclick = function (event) {
            const modal = document.getElementById('memberModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection