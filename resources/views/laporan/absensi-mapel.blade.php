@extends('layout.master')

@section('title', 'Laporan Absensi Mapel')

@section('content')
<div class="card border-0 shadow-sm laporan-card" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Laporan Absensi</h4>

        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('laporan.absensi-harian') }}"
               class="btn btn-outline-primary">
                Absensi Harian
            </a>

            <a href="{{ route('laporan.absensi-mapel') }}"
               class="btn btn-primary">
                Absensi Mapel
            </a>
        </div>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Tanggal Dari</label>
                <input type="date"
                       name="tanggal_dari"
                       class="form-control"
                       value="{{ request('tanggal_dari') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Tanggal Sampai</label>
                <input type="date"
                       name="tanggal_sampai"
                       class="form-control"
                       value="{{ request('tanggal_sampai') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>

                    @foreach($statusOptions as $status)
                        <option value="{{ $status }}"
                            {{ request('status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas</option>

                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}"
                            {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="form-select">
                    <option value="">Semua Mata Pelajaran</option>

                    @foreach($mataPelajaran as $mapel)
                        <option value="{{ $mapel->id }}"
                            {{ request('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Siswa</label>
                <select name="siswa_id" class="form-select">
                    <option value="">Semua Siswa</option>

                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}"
                            {{ request('siswa_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_siswa }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-9 d-flex align-items-end gap-2 flex-wrap">
                <button type="submit" class="btn btn-primary">
                    Filter
                </button>

                <a href="{{ route('laporan.absensi-mapel') }}"
                    class="btn btn-secondary">
                    Reset
                </a>

                <button type="submit"
                        name="cetak"
                        value="1"
                        class="btn btn-success px-4">
                    Cetak
                </button>

                <a href="{{ route('admin.export-absensi-mapel', request()->only([
                    'kelas_id',
                    'mata_pelajaran_id',
                    'tanggal_dari',
                    'tanggal_sampai',
                    'status',
                    'siswa_id'
                ])) }}"
                    class="btn btn-success px-4">
                    Export Excel
                </a>
            </div>
        </form>

        <div class="row mb-4">
            <div class="col">
                <div class="alert alert-success">
                    Hadir: {{ $statistik['hadir'] }}
                </div>
            </div>

            <div class="col">
                <div class="alert alert-warning">
                    Izin: {{ $statistik['izin'] }}
                </div>
            </div>

            <div class="col">
                <div class="alert alert-info">
                    Sakit: {{ $statistik['sakit'] }}
                </div>
            </div>

            <div class="col">
                <div class="alert alert-danger">
                    Alpha: {{ $statistik['alpha'] }}
                </div>
            </div>

            <div class="col">
                <div class="alert alert-secondary">
                    Terlambat: {{ $statistik['terlambat'] }}
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Catatan Guru (Opsional)</th>
                        <th>Nilai Disiplin</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($absensiMapel as $no => $a)
                        <tr>
                            <td>{{ $absensiMapel->firstItem() + $no }}</td>

                            <td>
                                {{ $a->tanggal
                                    ? \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td>{{ $a->siswa->nama_siswa ?? '-' }}</td>

                            <td>
                                {{ $a->jadwalPelajaran->kelas->nama_kelas ?? '-' }}
                            </td>

                            <td>{{ \Carbon\Carbon::parse($a->jadwalPelajaran->jam_mulai)->format('H:i') }}
                                    -
                                {{ \Carbon\Carbon::parse($a->jadwalPelajaran->jam_selesai)->format('H:i') }}</td>

                            <td>
                                {{ $a->jadwalPelajaran->mataPelajaran->nama_mapel ?? '-' }}
                            </td>

                            <td>{{ $a->status ?? '-' }}</td>

                            <td>
                                {{ $a->catatan ?? '-' }}
                            </td>

                            <td>{{ $a->scan_score ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                Belum ada data absensi mata pelajaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $absensiMapel->links() }}
    </div>
</div>

<style>
    .laporan-card,
    .laporan-card .card-body {
        overflow: visible !important;
    }

    .laporan-card .table-responsive {
        width: 100%;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    .laporan-card table {
        min-width: 1250px;
    }

    .laporan-card th,
    .laporan-card td {
        vertical-align: middle;
    }

    .laporan-card th {
        white-space: nowrap;
        text-align: center;
    }

    .laporan-card td:nth-child(2),
    .laporan-card td:nth-child(5) {
        white-space: nowrap;
    }
</style>
@endsection