@extends('layout.master')

@section('title', 'Monitor Absensi Wali Kelas')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-2">Monitor Kehadiran Harian Kelas {{ $kelas->nama_kelas ?? '-' }}</h3>
        <p class="text-muted">Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>

        <p class="text-muted small mb-4">
            *Status merupakan hasil rekapitulasi absensi seluruh mata pelajaran pada hari tersebut.
        </p>

        <div class="row g-3 mb-4">
            <div class="col-md">
                <div class="alert alert-success">Hadir: {{ $statistik['hadir'] ?? 0 }}</div>
            </div>
            <div class="col-md">
                <div class="alert alert-warning">Izin: {{ $statistik['izin'] ?? 0 }}</div>
            </div>
            <div class="col-md">
                <div class="alert alert-info">Sakit: {{ $statistik['sakit'] ?? 0 }}</div>
            </div>
            <div class="col-md">
                <div class="alert alert-danger">Alpha: {{ $statistik['alpha'] ?? 0 }}</div>
            </div>
            <div class="col-md">
                <div class="alert alert-primary">Terlambat: {{ $statistik['terlambat'] ?? 0 }}</div>
            </div>
            <div class="col-md">
                <div class="alert alert-secondary">Belum Absen: {{ $statistik['belum_absen'] ?? 0 }}</div>
            </div>
        </div>

        <div class="table-responsive monitor-table-wrapper">
            <table class="table table-bordered align-middle mb-0 monitor-table">
                <thead class="table-dark">
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-siswa">Nama Siswa</th>
                        <th class="col-status">Status</th>
                        <th class="col-terlambat">Terlambat</th>
                        <th class="col-jam">Jam Masuk</th>
                        <th class="col-keterangan">Keterangan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($siswa as $no => $item)
                        @php
                            $absen = $absensi[$item->id] ?? null;
                        @endphp

                        <tr>
                            <td class="text-center">{{ $no + 1 }}</td>

                            <td>{{ $item->nama_siswa ?? '-' }}</td>

                            <td class="text-center">
                                @if($absen)
                                    {{ $absen->status }}
                                @else
                                    <span class="text-muted">Belum Absen</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if(!$absen)
                                    -
                                @elseif($absen->terlambat)
                                    <span class="badge bg-warning text-dark">Ya</span>
                                @else
                                    <span class="badge bg-success">Tidak</span>
                                @endif
                            </td>

                            <td class="">
                                {{ $absen && $absen->jam_masuk
                                ? \Carbon\Carbon::parse($absen->jam_masuk)->format('H:i')
                                : '-' }}</td>

                            <td>
                                {{ $absen->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada siswa di kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('dashboard') }}" class="btn btn-light border">Kembali</a>

    </div>
</div>

<style>
    .monitor-table-wrapper {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .monitor-table {
        width: 100%;
        min-width: 900px;
        margin-bottom: 0;
    }

    .monitor-table th,
    .monitor-table td {
        vertical-align: middle;
    }

    .monitor-table th {
        white-space: nowrap;
        text-align: center;
    }

    .col-no {
        width: 60px;
        min-width: 60px;
    }

    .col-siswa {
        min-width: 190px;
    }

    .col-status {
        min-width: 110px;
    }

    .col-terlambat {
        min-width: 120px;
    }

    .col-jam {
        min-width: 120px;
    }

    .col-keterangan {
        min-width: 350px;
        white-space: normal;
        overflow-wrap: break-word;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 18px !important;
        }

        .monitor-table {
            font-size: 13px;
        }

        .monitor-table th,
        .monitor-table td {
            padding: 10px 8px;
        }
    }
</style>
@endsection