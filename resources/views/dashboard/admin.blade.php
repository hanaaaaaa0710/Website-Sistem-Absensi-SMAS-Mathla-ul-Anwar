@extends('layout.master')
@section('title','Dashboard Admin')

@section('content')
<h4 class="mb-4">Dashboard Admin</h4>

<div class="row g-3 mb-4">

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 bg-primary-subtle">
            <div class="card-body text-center py-4">
                <h6 class="text-primary mb-2">Total Guru</h6>
                <h2 class="fw-bold mb-0">
                    {{ $stats['total_guru'] ?? 0 }}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 bg-success-subtle">
            <div class="card-body text-center py-4">
                <h6 class="text-success mb-2">Total Siswa</h6>
                <h2 class="fw-bold mb-0">
                    {{ $stats['total_siswa'] ?? 0 }}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 bg-warning-subtle">
            <div class="card-body text-center py-4">
                <h6 class="text-warning mb-2">Total Kelas</h6>
                <h2 class="fw-bold mb-0">
                    {{ $stats['total_kelas'] ?? 0 }}
                </h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100 bg-info-subtle">
            <div class="card-body text-center py-4">
                <h6 class="text-info mb-2">Total Users</h6>
                <h2 class="fw-bold mb-0">
                    {{ $stats['total_users'] ?? 0 }}
                </h2>
            </div>
        </div>
    </div>

</div>

<h5>Data Guru</h5>

<div class="table-responsive mb-4">
    <table class="table table-bordered align-middle" style="min-width: 700px;">
        <thead>
            <tr>
                <th class="text-center" style="width: 70px;">No</th>
                <th>Nama Guru</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data_guru as $no => $guru)
                <tr>
                    <td class="text-center">{{ $no + 1 }}</td>
                    <td>{{ $guru->nama_guru ?? '-' }}</td>
                    <td>{{ $guru->user?->email ?? '-' }}</td>
                    <td>{{ $guru->status ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">
                        Belum ada data guru.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div id="absensi-mapel-section">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Absensi Mapel Terbaru</h5>

        <a href="{{ route('laporan.absensi-mapel') }}"
           class="btn btn-outline-primary">
            Lihat Semua Absensi →
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle"
               style="min-width: 1050px;">
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-siswa">Siswa</th>
                    <th class="col-kelas">Kelas</th>
                    <th class="col-mapel">Mata Pelajaran</th>
                    <th class="col-status">Status</th>
                    <th class="col-catatan">Catatan Guru (Opsional)</th>
                    <th class="col-nilai">Nilai Disiplin</th>
                    <th class="col-tanggal">Tanggal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($absensi as $index => $a)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $a->siswa?->nama_siswa ?? '-' }}</td>

                        <td>
                            {{ $a->jadwalPelajaran?->kelas?->nama_kelas ?? '-' }}
                        </td>

                        <td>
                            {{ $a->jadwalPelajaran?->mataPelajaran?->nama_mapel ?? '-' }}
                        </td>

                        <td>{{ $a->status ?? '-' }}</td>

                        <td>{{ $a->catatan ?? '-' }}</td>

                        <td>{{ $a->scan_score ?? '-' }}</td>

                        <td class="text-nowrap">
                            {{ $a->tanggal
                                ? \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y')
                                : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            Belum ada data absensi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    .table th {
        text-align: center;
        white-space: nowrap;
    }

    .col-no {
        width: 70px;
        min-width: 70px;
        text-align: center;
    }

    .col-siswa {
        min-width: 170px;
    }

    .col-kelas {
        min-width: 90px;
    }

    .col-mapel {
        min-width: 150px;
    }

    .col-status {
        min-width: 100px;
    }

    .col-catatan {
        min-width: 290px;
    }

    .col-nilai {
        min-width: 120px;
    }

    .col-tanggal {
        min-width: 125px;
        white-space: nowrap;
    }
</style>
@endsection