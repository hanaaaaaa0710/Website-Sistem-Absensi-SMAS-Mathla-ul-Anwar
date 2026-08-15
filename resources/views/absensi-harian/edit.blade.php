@extends('layout.master')
@section('title', isset($absensi) ? 'Edit Absensi Harian' : 'Tambah Absensi Harian')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ isset($absensi) ? 'Edit Absensi Harian' : 'Tambah Absensi Harian' }}</h5>

        <form action="{{ isset($absensi) ? route('absensi-harian.update',$absensi->id) : route('absensi-harian.store') }}" method="POST">
            @csrf
            @if(isset($absensi))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label>Siswa</label>
                <select name="siswa_id" class="form-select" required>
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ old('siswa_id', $absensi->siswa_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_siswa ?? $s->nama ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $absensi->tanggal ?? old('tanggal') }}" required>
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    @foreach(['Hadir','Alpha','Izin','Sakit'] as $status)
                        <option value="{{ $status }}" {{ (isset($absensi) && $absensi->status==$status) ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="catatan" class="form-control">{{ $absensi->catatan ?? old('catatan') }}</textarea>
            </div>

            <div class="mb-3">
                <label>Nilai Disiplin</label>
                <input type="number" name="scan_score" class="form-control" min="0" max="100" value="{{ $absensi->scan_score ?? old('scan_score') }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ isset($absensi) ? 'Update' : 'Simpan' }}</button>
        </form>
    </div>
</div>
@endsection