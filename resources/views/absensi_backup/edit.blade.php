@extends('layout.master')

@section('title', 'Edit Absensi')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
  <div class="card-body p-4">
    <h3 class="fw-bold mb-4">Edit Absensi Siswa</h3>

    <form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama Siswa</label>
        <select name="siswa_id" class="form-select" required>
          @foreach($data_siswa as $siswa)
            <option value="{{ $siswa->id }}" {{ $absensi->siswa_id == $siswa->id ? 'selected' : '' }}>
              {{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="{{ $absensi->tanggal }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Jam Masuk</label>
        <input type="time" name="jam_masuk" class="form-control" value="{{ $absensi->jam_masuk }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <option value="Hadir" {{ $absensi->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
          <option value="Izin" {{ $absensi->status == 'Izin' ? 'selected' : '' }}>Izin</option>
          <option value="Sakit" {{ $absensi->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
          <option value="Alpha" {{ $absensi->status == 'Alpha' ? 'selected' : '' }}>Alpha</option>
          <option value="Terlambat" {{ $absensi->status == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Status Notifikasi</label>
        <select name="status_notifikasi" class="form-select" required>
          <option value="Menunggu" {{ $absensi->status_notifikasi == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
          <option value="Berhasil" {{ $absensi->status_notifikasi == 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
          <option value="Gagal" {{ $absensi->status_notifikasi == 'Gagal' ? 'selected' : '' }}>Gagal</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="3">{{ $absensi->keterangan }}</textarea>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('absensi.index') }}" class="btn btn-light border">Kembali</a>
      </div>
    </form>
  </div>
</div>
@endsection