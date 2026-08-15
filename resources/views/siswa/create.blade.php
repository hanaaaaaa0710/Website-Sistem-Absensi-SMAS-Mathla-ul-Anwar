@extends('layout.master')

@section('title', 'Tambah Data Siswa')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-4">Tambah Data Siswa</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Data belum berhasil disimpan.</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.store') }}" method="POST">
            @csrf

            <h5 class="fw-bold mb-3">Data Siswa</h5>

            <div class="mb-3">
                <label class="form-label">Nama Siswa</label>
                <input
                    type="text"
                    name="nama_siswa"
                    class="form-control"
                    value="{{ old('nama_siswa') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Tempat, Tanggal Lahir</label>
                <input
                    type="text"
                    name="ttl"
                    class="form-control"
                    value="{{ old('ttl') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>

                <select name="jenis_kelamin" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>
                        Laki-laki
                    </option>
                    <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>
                        Perempuan
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas</label>

                <select name="kelas_id" class="form-select" required>
                    <option value="">-- Pilih Kelas --</option>

                    @foreach($list_kelas as $kelas)
                        <option
                            value="{{ $kelas->id }}"
                            {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}
                        >
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <hr class="my-4">

            <h5 class="fw-bold mb-3">Akun Orang Tua/Wali</h5>

            <div class="mb-3">
                <label class="form-label">Nama Orang Tua/Wali</label>
                <input
                    type="text"
                    name="nama_ortu"
                    class="form-control"
                    value="{{ old('nama_ortu') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Hubungan dengan Siswa</label>

                <select name="hubungan_ortu" class="form-select" required>
                    <option value="">-- Pilih Hubungan --</option>

                    <option value="Ayah"
                        {{ old('hubungan_ortu') === 'Ayah' ? 'selected' : '' }}>
                        Ayah
                    </option>

                    <option value="Ibu"
                        {{ old('hubungan_ortu') === 'Ibu' ? 'selected' : '' }}>
                        Ibu
                    </option>

                    <option value="Wali"
                        {{ old('hubungan_ortu') === 'Wali' ? 'selected' : '' }}>
                        Wali
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor HP Orang Tua/Wali</label>
                <input
                    type="text"
                    name="no_hp_ortu"
                    class="form-control"
                    value="{{ old('no_hp_ortu') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email Login</label>
                <input
                    type="email"
                    name="email_ortu"
                    class="form-control"
                    value="{{ old('email_ortu') }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input
                    type="password"
                    name="password_ortu"
                    class="form-control"
                    minlength="6"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input
                    type="password"
                    name="password_ortu_confirmation"
                    class="form-control"
                    minlength="6"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>

                <select name="status" class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option value="Aktif" {{ old('status') === 'Aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>
                    <option
                        value="Tidak Aktif"
                        {{ old('status') === 'Tidak Aktif' ? 'selected' : '' }}
                    >
                        Tidak Aktif
                    </option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="{{ route('siswa.index') }}" class="btn btn-light">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection