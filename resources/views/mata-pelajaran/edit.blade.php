@extends('layout.master')

@section('title', 'Edit Mata Pelajaran')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Edit Mata Pelajaran</h4>

        <form action="{{ route('mata-pelajaran.update', ['mata_pelajaran' => $mataPelajaran->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Mata Pelajaran</label>
                <input type="text" name="nama_mapel" class="form-control"
                       value="{{ old('nama_mapel', $mataPelajaran->nama_mapel) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kode Mata Pelajaran</label>
                <input type="text" name="kode_mapel" class="form-control"
                       value="{{ old('kode_mapel', $mataPelajaran->kode_mapel) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">SKS</label>
                <input type="number" name="sks" class="form-control" value="{{ old('sks', $mataPelajaran->sks) }}" min="1" max="4" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="is_aktif" class="form-select" required>
                    <option value="1" {{ old('is_aktif', $mataPelajaran->is_aktif) == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_aktif', $mataPelajaran->is_aktif) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $mataPelajaran->deskripsi) }}</textarea>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('mata-pelajaran.index') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection