@extends('layout.master')

@section('title', 'Edit Data Siswa')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-4">Edit Data Siswa</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Data belum berhasil diperbarui.</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <h5 class="fw-bold mb-3">Data Siswa</h5>

            <div class="mb-3">
                <label class="form-label">Nama Siswa</label>
                <input
                    type="text"
                    name="nama_siswa"
                    class="form-control"
                    value="{{ old('nama_siswa', $siswa->nama_siswa) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Kelamin</label>

                <select name="jenis_kelamin" class="form-select" required>
                    <option
                        value="L"
                        {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}
                    >
                        Laki-laki
                    </option>

                    <option
                        value="P"
                        {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}
                    >
                        Perempuan
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tempat, Tanggal Lahir</label>
                <input
                    type="text"
                    name="ttl"
                    class="form-control"
                    value="{{ old('ttl', $siswa->ttl) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Kelas</label>

                <select name="kelas_id" class="form-select" required>
                    @foreach($kelas as $k)
                        <option
                            value="{{ $k->id }}"
                            {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}
                        >
                            {{ $k->nama_kelas }}
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
                    value="{{ old('nama_ortu', $siswa->nama_ortu) }}"
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
                    value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Email Login</label>
                <input
                    type="email"
                    name="email_ortu"
                    class="form-control"
                    value="{{ old('email_ortu', $siswa->user?->email) }}"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Password Baru
                </label>

                <input
                    type="password"
                    name="password_ortu"
                    class="form-control"
                    minlength="6"
                >

                <small class="text-muted">
                    Kosongkan apabila password tidak ingin diubah.
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input
                    type="password"
                    name="password_ortu_confirmation"
                    class="form-control"
                    minlength="6"
                >
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>

                <select name="status" class="form-select" required>
                    <option
                        value="Aktif"
                        {{ old('status', $siswa->status) === 'Aktif' ? 'selected' : '' }}
                    >
                        Aktif
                    </option>

                    <option
                        value="Tidak Aktif"
                        {{ old('status', $siswa->status) === 'Tidak Aktif' ? 'selected' : '' }}
                    >
                        Tidak Aktif
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Update
            </button>

            <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                Kembali
            </a>
        </form>
    </div>
</div>
@endsection