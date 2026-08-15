@extends('layout.master')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Tambah Jadwal Pelajaran</h4>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('jadwal-pelajaran.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="form-select" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($mataPelajaran as $m)
                        <option value="{{ $m->id }}" {{ old('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Guru</label>
                <select name="guru_id" class="form-select" required>
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guru as $g)
                        <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->nama_guru }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Hari</label>
                <select name="hari" class="form-select" required>
                    <option value="">-- Pilih Hari --</option>
                    <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                    <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                    <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                    <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                    <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                    <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select" required>
                    <option value="">-- Pilih Semester --</option>
                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Ganjil</option>
                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" class="form-control"
                       placeholder="2026/2027" value="{{ old('tahun_ajaran') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jam Mulai</label>
                 <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection