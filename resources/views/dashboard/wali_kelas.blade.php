@extends('layout.master')
@section('title','Dashboard Wali Kelas')

@section('content')
<h1>Dashboard Wali Kelas</h1>

@if(session('error'))
    <div class="alert alert-warning">
        {{ session('error') }}
    </div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h5 class="mb-0 fw-bold text-primary">
                Statistik Kehadiran Bulan Ini
            </h5>

            @if(!empty($statistik['bulan']))
                <span class="badge bg-light text-dark fs-6">
                    {{ $statistik['bulan'] }}
                </span>
            @endif
        </div>

        <div class="row g-3">

            <div class="col-md">
                <div class="card border-0 shadow-sm h-100 bg-success-subtle">
                    <div class="card-body text-center py-4">
                        <h6 class="text-success mb-2">Hadir</h6>
                        <h2 class="fw-bold mb-0">
                            {{ $statistik['hadir'] ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card border-0 shadow-sm h-100 bg-warning-subtle">
                    <div class="card-body text-center py-4">
                        <h6 class="text-warning mb-2">Izin</h6>
                        <h2 class="fw-bold mb-0">
                            {{ $statistik['izin'] ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card border-0 shadow-sm h-100 bg-info-subtle">
                    <div class="card-body text-center py-4">
                        <h6 class="text-info mb-2">Sakit</h6>
                        <h2 class="fw-bold mb-0">
                            {{ $statistik['sakit'] ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card border-0 shadow-sm h-100 bg-danger-subtle">
                    <div class="card-body text-center py-4">
                        <h6 class="text-danger mb-2">Alpha</h6>
                        <h2 class="fw-bold mb-0">
                            {{ $statistik['alpha'] ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card border-0 shadow-sm h-100 bg-primary-subtle">
                    <div class="card-body text-center py-4">
                        <h6 class="text-primary mb-2">Terlambat</h6>
                        <h2 class="fw-bold mb-0">
                            {{ $statistik['terlambat'] ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>Rekap Kehadiran Terbaru</h5>

    <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-siswa">Siswa</th>
                    <th class="col-tanggal">Tanggal</th>
                    <th class="col-status">Status</th>
                    <th class="col-keterangan">Keterangan</th>
                    <th class="col-nilai">Nilai Disiplin</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($absensiHarian ?? []) as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->siswa?->nama_siswa ?? '-' }}</td>
                        <td>{{ $item->tanggal
                                ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')
                                : '-'}}</td>
                        <td>{{ $item->status ?? '-' }}</td>
                        <td>{{ $item->keterangan ?? '-' }}</td>
                        <td>{{ $item->scan_score ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Belum ada data absensi harian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>Absensi Mapel Terbaru</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-siswa">Siswa</th>
                        <th class="col-tanggal">Tanggal</th>
                        <th class="col-status">Status</th>
                        <th class="col-catatan">Catatan Guru (Opsional)</th>
                        <th class="col-nilai">Nilai Disiplin</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse(($absensiMapel ?? []) as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->siswa?->nama_siswa ?? '-' }}</td>
                            <td>
                                {{ $item->tanggal
                                    ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')
                                    : '-' }}
                            </td>
                            <td>{{ $item->status ?? '-' }}</td>
                            <td>{{ $item->catatan ?? '-' }}</td>
                            <td>{{ $item->scan_score ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada data absensi harian.
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
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive .table {
        width: 100%;
        min-width: 900px;
        margin-bottom: 0;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .table th {
        white-space: nowrap;
        text-align: center;
    }

    .col-no {
        width: 55px;
        min-width: 55px;
        text-align: center;
    }

    .col-siswa {
        min-width: 180px;
    }

    .col-kelas {
        min-width: 100px;
    }

    .col-mapel {
        min-width: 160px;
    }

    .col-status {
        min-width: 100px;
        text-align: center;
    }

    .col-keterangan {
        min-width: 360px;
        white-space: normal;
        overflow-wrap: break-word;
    }

    .col-catatan {
        min-width: 260px;
        white-space: normal;
        overflow-wrap: break-word;
    }

    .col-nilai {
        min-width: 120px;
        text-align: center;
    }

    .col-tanggal {
        min-width: 125px;
        white-space: nowrap;
        text-align: center;
    }
</style>

@endsection