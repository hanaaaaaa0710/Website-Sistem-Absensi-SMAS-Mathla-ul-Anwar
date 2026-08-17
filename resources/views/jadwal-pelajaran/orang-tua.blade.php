@extends('layout.master')

@section('title', 'Jadwal Pelajaran Anak')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-1">Jadwal Pelajaran Anak</h3>

        <p class="text-muted mb-4">
            Jadwal pelajaran
            <strong>{{ $siswa->nama_siswa ?? '-' }}</strong>
            dari kelas
            <strong>{{ $siswa->kelas->nama_kelas ?? '-' }}</strong>.
        </p>

        <div class="table-responsive">
            <table class="table table-bordered align-middle" style="min-width: 850px;">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Hari</th>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru</th>
                        <th>Ruang</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($jadwal as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>{{ $item->hari ?? '-' }}</td>

                            <td class="text-nowrap">
                                {{ $item->jam_mulai
                                    ? \Carbon\Carbon::parse($item->jam_mulai)->format('H:i')
                                    : '-' }}

                                -

                                {{ $item->jam_selesai
                                    ? \Carbon\Carbon::parse($item->jam_selesai)->format('H:i')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $item->mataPelajaran->nama_mapel ?? '-' }}
                            </td>

                            <td>
                                {{ $item->guru->nama_guru ?? '-' }}
                            </td>

                            <td>
                                {{ $item->ruang_kelas
                                    ?? $item->kelas->nama_kelas
                                    ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Jadwal pelajaran belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table-responsive {
        width: 100%;
        max-width: 100%;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    .table th {
        white-space: nowrap;
        text-align: center;
    }

    .table td {
        vertical-align: middle;
    }
</style>
@endsection