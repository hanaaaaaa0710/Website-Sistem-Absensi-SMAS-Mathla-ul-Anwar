@extends('layout.master')
@section('title', 'Tambah Guru')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Tambah Guru</h4>

        <form action="{{ route('guru.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Guru</label>
                <input type="text" name="nama_guru" class="form-control" value="{{ old('nama_guru') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="form-select" required>
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mataPelajaran as $m)
                        <option value="{{ $m->id }}" {{ old('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas Wali</label>

                <select
                    name="kelas_wali_id"
                    class="form-select">

                    <option value="">
                        -- Bukan Wali Kelas --
                    </option>

                    @foreach($kelas as $k)

                        <option
                            value="{{ $k->id }}"
                            {{ old('kelas_wali_id')==$k->id?'selected':'' }}>

                            {{ $k->nama_kelas }}

                        </option>

                    @endforeach

                </select>
            </div>

            <div class="mb-3">

                <label class="form-label">
                    Tahun Ajaran Wali
                </label>

                <input
                    type="text"
                    name="tahun_ajaran_wali"
                    class="form-control"
                    placeholder="Contoh : 2026/2027"
                    value="{{ old('tahun_ajaran_wali') }}">

            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ old('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('guru.index') }}" class="btn btn-light">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection