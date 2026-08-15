@extends('layout.master')
@section('title', 'Edit Kelas')

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Edit Kelas</h4>

        <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Kelas</label>
                <input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tingkat</label>
                <select name="tingkat" class="form-select" required>
                    <option value="10" {{ old('tingkat', $kelas->tingkat ?? '') == 10 ? 'selected' : '' }}>X</option>
                    <option value="11" {{ old('tingkat', $kelas->tingkat ?? '') == 11 ? 'selected' : '' }}>XI</option>
                    <option value="12" {{ old('tingkat', $kelas->tingkat ?? '') == 12 ? 'selected' : '' }}>XII</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" class="form-control" value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection