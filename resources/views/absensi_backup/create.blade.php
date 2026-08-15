@extends('layout.master')

@section('title', 'Tambah Absensi')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
  <div class="card-body p-4">
    <h3 class="fw-bold mb-4">Tambah Absensi Siswa</h3>

    <form action="{{ route('absensi.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama Siswa</label>
        <select name="siswa_id" class="form-select" required>
          <option value="">-- Pilih Siswa --</option>
          @foreach($data_siswa as $siswa)
            <option value="{{ $siswa->id }}">{{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? '-' }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Jam Masuk</label>
        <input type="time" name="jam_masuk" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <option value="">-- Pilih Status --</option>
          <option value="Hadir">Hadir</option>
          <option value="Izin">Izin</option>
          <option value="Sakit">Sakit</option>
          <option value="Alpha">Alpha</option>
          <option value="Terlambat">Terlambat</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Status Notifikasi</label>
        <select name="status_notifikasi" class="form-select" required>
          <option value="">Semua Status</option>
          <option value="sudah_dibaca" {{ request('status_notifikasi') == 'sudah_dibaca' ? 'selected' : '' }}>
            Sudah Dibaca
          </option>
          <option value="belum_dibaca" {{ request('status_notifikasi') == 'belum_dibaca' ? 'selected' : '' }}>
            Belum Dibaca
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Keterangan</label>
        <textarea name="keterangan" class="form-control" rows="3"></textarea>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('absensi.index') }}" class="btn btn-light border">Kembali</a>
      </div>
    </form>
  </div>
</div>
@endsection