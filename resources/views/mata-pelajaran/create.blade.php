@extends('layout.master')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Tambah Mata Pelajaran</h4>

        <form action="{{ route('mata-pelajaran.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" class="form-control"
                       value="{{ old('nama_mapel') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kode Mata Pelajaran</label>
                <input type="text" name="kode_mapel" class="form-control"
                       value="{{ old('kode_mapel') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">SKS</label>
                <input type="number" name="sks" class="form-control" value="{{ old('sks', 1) }}" min="1" max="4" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="is_aktif" class="form-select" required>
                    <option value="1" {{ old('is_aktif', 1) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                Simpan
            </button>

            <a href="{{ route('mata-pelajaran.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>
@endsection