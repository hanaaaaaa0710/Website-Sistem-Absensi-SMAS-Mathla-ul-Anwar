@extends('layout.master')
@section('title', 'Tambah Kelas')

@section('content')
<div class="card border-0 shadow-sm p-4" style="border-radius:18px;">
    <h4 class="fw-bold mb-3">Tambah Kelas</h4>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('kelas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas') }}" required>
            {{-- Kode kelas otomatis --}}
            <input type="hidden" name="kode_kelas" value="{{ strtoupper(substr(old('nama_kelas') ?? '', 0, 3)) . rand(10,99) }}">
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
            <input type="text" name="tahun_ajaran" class="form-control" 
                value="{{ old('tahun_ajaran', date('Y') . '/' . (date('Y')+1)) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection