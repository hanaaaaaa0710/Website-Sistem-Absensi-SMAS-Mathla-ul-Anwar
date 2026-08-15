@extends('layout.master')

@section('title', 'Jadwal Pelajaran Anak')

@section('styles')
<style>
    .aksi-col {
        min-width: 270px;
    }

    .aksi-btn {
        min-width: 115px;
        text-align: center;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-4">Jadwal Mengajar Saya</h3>

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" style="width:70px;">No</th>
                    <th>Hari</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Jam</th>
                    <th>Ruang</th>
                    <th class="text-center aksi-col">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($jadwal as $no => $item)
                    <tr>
                        <td class="text-center">{{ $no + 1 }}</td>
                        <td>{{ $item->hari }}</td>
                        <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $item->mataPelajaran->nama_mapel ?? '-' }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                        </td>
                        <td>{{ $item->ruang_kelas ?? '-' }}</td>
                        <td class="text-center align-middle aksi-col">
                            <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                                <a href="{{ route('guru.absensi-mapel.buka-sesi', $item->id) }}"
                                   class="btn btn-sm btn-primary aksi-btn">
                                    Isi Absensi
                                </a>

                                <a href="{{ route('guru.absensi-mapel.edit-sesi', $item->id) }}"
                                   class="btn btn-sm btn-warning text-white aksi-btn">
                                    Edit / Update
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            Jadwal mengajar belum tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('dashboard') }}" class="btn btn-light border">Kembali</a>
    </div>
</div>
@endsection