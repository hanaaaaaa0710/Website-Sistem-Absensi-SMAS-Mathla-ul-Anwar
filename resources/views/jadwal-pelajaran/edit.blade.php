@extends('layout.master')

@section('title', 'Edit Jadwal Pelajaran')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Edit Jadwal Pelajaran</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('jadwal-pelajaran.update', ['jadwal_pelajaran' => $jadwalPelajaran->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select" required>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}"
                            {{ old('kelas_id', $jadwalPelajaran->kelas_id) == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="form-select" required>
                    @foreach($mataPelajaran as $m)
                        <option value="{{ $m->id }}"
                            {{ old('mata_pelajaran_id', $jadwalPelajaran->mata_pelajaran_id) == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Guru</label>
                <select name="guru_id" class="form-select" required>
                    @foreach($guru as $g)
                        <option value="{{ $g->id }}"
                            {{ old('guru_id', $jadwalPelajaran->guru_id) == $g->id ? 'selected' : '' }}>
                            {{ $g->nama_guru }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Hari</label>
                <select name="hari" class="form-select" required>
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                        <option value="{{ $hari }}"
                            {{ old('hari', $jadwalPelajaran->hari) == $hari ? 'selected' : '' }}>
                            {{ $hari }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Semester</label>
                <select name="semester" class="form-select" required>
                    <option value="1" {{ (string) old('semester', $jadwalPelajaran->semester ?? $jadwal->semester ?? '') === '1' ? 'selected' : '' }}>
                        Ganjil
                    </option>
                    <option value="2" {{ (string) old('semester', $jadwalPelajaran->semester ?? $jadwal->semester ?? '') === '2' ? 'selected' : '' }}>
                        Genap
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" class="form-control"
                        value="{{ old('tahun_ajaran', $jadwalPelajaran->tahun_ajaran ?? $jadwal->tahun_ajaran ?? '2025/2026') }}"
                        required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control"
                        value="{{ old('jam_mulai', substr($jadwalPelajaran->jam_mulai, 0, 5)) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control"
                       value="{{ old('jam_selesai', substr($jadwalPelajaran->jam_selesai, 0, 5)) }}" required>
            </div>
            <button type="submit" class="btn btn-primary">
                Update
            </button>

            <a href="{{ route('jadwal-pelajaran.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>
@endsection