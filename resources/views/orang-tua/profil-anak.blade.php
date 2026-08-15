@extends('layout.master')

@section('title', 'Profil Anak')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-1">Profil Anak</h3>
        <p class="text-muted mb-4">
            Informasi siswa yang terhubung dengan akun orang tua/wali.
        </p>

        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th style="width: 200px;">NIS</th>
                        <td>{{ $siswa->nis ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Nama Anak</th>
                        <td>{{ $siswa->nama_siswa ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>
                            {{ $siswa->jenis_kelamin === 'L'
                                ? 'Laki-laki'
                                : ($siswa->jenis_kelamin === 'P'
                                    ? 'Perempuan'
                                    : '-') }}
                        </td>
                    </tr>

                    <tr>
                        <th>Tempat, Tanggal Lahir</th>
                        <td>{{ $siswa->ttl ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Kelas</th>
                        <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Status Siswa</th>
                        <td>
                            @if($siswa->status === 'Aktif')
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">
                                    {{ $siswa->status ?? '-' }}
                                </span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Tahun Ajaran</th>
                        <td>{{ $siswa->tahun_ajaran ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <h5 class="fw-bold mb-3">Data Orang Tua/Wali</h5>

                <table class="table table-borderless">
                    <tr>
                        <th style="width: 200px;">Nama</th>
                        <td>
                            {{ $siswa->nama_ortu
                                ?? $user->name
                                ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Hubungan</th>
                        <td>{{ $siswa->hubungan_ortu ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Nomor HP</th>
                        <td>{{ $siswa->no_hp_ortu ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Email Login</th>
                        <td>{{ $user->email ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection