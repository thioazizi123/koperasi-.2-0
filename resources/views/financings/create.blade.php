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

            <div class="form-group">
                <label class="form-label"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #334155;">Anggota</label>
                <select name="member_id" class="form-control" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem;">
                    <option value="">-- Pilih Anggota --</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }} - {{ $member->address }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label class="form-label"
                    style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #334155;">Tipe Pembiayaan</label>
                <select name="type" class="form-control" required
                    style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 0.5rem;">
                    <option value="Modal Usaha">Modal Usaha</option>
                    <option value="Renovasi Rumah">Renovasi Rumah</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Multiguna">Multiguna</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label class="form-label"
                        style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #334155;">Jumlah Pengajuan
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
            <a href="{{ route('financings.index') }}" class="btn" style="background: #e2e8f0; color: #334155; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 500; cursor: pointer; text-decoration: none; display: inline-block; margin-right: 0.5rem;">
                Batal
            </a>
            <button type="submit" class="btn btn-primary" style="background: #e11d48; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: 500; cursor: pointer;">
                Ajukan Pembiayaan
            </button>
        </div>
        </form>
    </div>
@endsection