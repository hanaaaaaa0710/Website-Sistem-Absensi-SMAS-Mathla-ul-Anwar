@extends('layout.master')

@section('title', 'Absensi Per Mapel')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-3">Absensi Per Mapel</h4>

        <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Status</th>
                    <th>Catatan Guru (Opsional)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensiMapel as $item)
                    <tr>
                        <td class="tanggal-mapel">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                        <td class="jam-mapel">
                            {{ $item->jadwalPelajaran->jam_mulai
                                ? \Carbon\Carbon::parse($item->jadwalPelajaran->jam_mulai)->format('H:i')
                                : '-' }}
                            -
                            {{ $item->jadwalPelajaran->jam_selesai
                                ? \Carbon\Carbon::parse($item->jadwalPelajaran->jam_selesai)->format('H:i')
                                : '-' }}
                        </td>
                        <td class="nama-mapel">{{ $item->jadwalPelajaran->mataPelajaran->nama_mapel ?? '-' }}</td>
                        <td class="nama-guru">{{ $item->jadwalPelajaran->guru->nama_guru ?? '-' }}</td>
                        <td class="status-mapel">{{ $item->status ?? '-' }}</td>
                        <td class="catatan-mapel">{{ $item->catatan ?? $item->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data absensi mapel.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .table {
        min-width: 950px;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .table th {
        white-space: nowrap;
    }

    .tanggal-mapel {
        min-width: 125px;
        white-space: nowrap;
    }

    .jam-mapel {
        min-width: 115px;
        white-space: nowrap;
    }

    .nama-mapel {
        min-width: 160px;
    }

    .nama-guru {
        min-width: 170px;
    }

    .status-mapel {
        min-width: 110px;
    }

    .catatan-mapel {
        min-width: 260px;
        white-space: normal;
    }
</style>
@endsection