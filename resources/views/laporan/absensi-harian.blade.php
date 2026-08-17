@extends('layout.master')

@section('title', 'Laporan Absensi Harian')

@section('styles')
<style>
@media print {
    .sidebar,
    .topbar,
    .navbar,
    .no-print,
    .d-print-none,
    form.no-print {
        display: none !important;
    }

    html,
    body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #ffffff !important;
    }

    html,
    body,
    .body-wrapper,
    .page-wrapper,
    .main-wrapper,
    .container-fluid,
    main,
    .content-wrapper,
    .card,
    .card-body {
        width: 100% !important;
        height: auto !important;
        min-height: 0 !important;
        max-height: none !important;

        overflow: visible !important;
        overflow-x: visible !important;
        overflow-y: visible !important;

        position: static !important;
        transform: none !important;

        margin: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .table-responsive,
    .overflow-auto,
    .overflow-x-auto,
    .overflow-y-auto {
        width: 100% !important;
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }

    .body-wrapper,
    .page-wrapper,
    .main-wrapper,
    .container-fluid,
    main,
    .content-wrapper {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
        left: 0 !important;
    }

    .card,
    .card-body {
        width: 100% !important;
        max-width: none !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }

    h4 {
        text-align: center;
        font-size: 20px !important;
        margin: 0 0 6px !important;
    }

    .d-print-block {
        display: block !important;
        text-align: center;
        font-size: 12px !important;
        margin: 0 0 12px !important;
    }

    .statistik-print {
        display: flex !important;
        flex-wrap: nowrap !important;
        justify-content: center !important;
        gap: 8px !important;
        margin: 0 0 12px !important;
    }

    .statistik-print .col-md-2 {
        width: auto !important;
        flex: 0 0 auto !important;
        padding: 0 !important;
    }

    .statistik-print .alert {
        min-width: 95px !important;
        margin: 0 !important;
        padding: 7px 12px !important;
        text-align: center !important;
        white-space: nowrap !important;
        font-size: 11px !important;
    }

    table {
        width: 100% !important;
        table-layout: fixed !important;
        margin: 0 !important;
        font-size: 10px !important;
        page-break-inside: auto !important;
    }

    thead {
        display: table-header-group !important;
    }

    tbody {
        display: table-row-group !important;
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }

    tr {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        page-break-after: auto !important;
    }

    table th,
    table td {
        padding: 6px 5px !important;
        vertical-align: middle !important;
        overflow-wrap: break-word !important;
    }

    table th:nth-child(1),
    table td:nth-child(1) { width: 4% !important; }

    table th:nth-child(2),
    table td:nth-child(2) { width: 10% !important; }

    table th:nth-child(3),
    table td:nth-child(3) { width: 16% !important; }

    table th:nth-child(4),
    table td:nth-child(4) { width: 8% !important; }

    table th:nth-child(5),
    table td:nth-child(5) { width: 10% !important; }

    table th:nth-child(6),
    table td:nth-child(6) { width: 11% !important; }

    table th:nth-child(7),
    table td:nth-child(7) { width: 14% !important; }

    table th:nth-child(8),
    table td:nth-child(8) { width: 14% !important; }

    table th:nth-child(9),
    table td:nth-child(9) { width: 10% !important; }

    thead {
        display: table-header-group !important;
    }

    tfoot {
        display: table-footer-group !important;
    }

    table {
        page-break-inside: auto !important;
    }

    tr {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        page-break-after: auto !important;
    }

    .statistik-print {
        page-break-after: avoid !important;
    }

    h4,
    .d-print-block {
        page-break-after: avoid !important;
    }

    @page {
        size: A4 landscape;
        margin: 8mm;
    }

}


.laporan-card {
    width: 100%;
    max-width: 100%;
    min-width: 0;
}

.laporan-card .card-body {
    width: 100%;
    max-width: 100%;
    min-width: 0;
    overflow: hidden !important;
}

.laporan-card .table-responsive {
    display: block;
    width: 100%;
    max-width: 100%;
    min-width: 0;
    overflow-x: auto !important;
    overflow-y: hidden !important;
    -webkit-overflow-scrolling: touch;
}

.laporan-card .table-responsive table {
    width: 1250px !important;
    min-width: 1250px !important;
    max-width: none !important;
    margin-bottom: 0;
}

.laporan-card th,
.laporan-card td {
    vertical-align: middle;
}

.laporan-card th {
    text-align: center;
    white-space: nowrap;
}

/* No */
.laporan-card th:nth-child(1),
.laporan-card td:nth-child(1) {
    width: 55px;
    min-width: 55px;
    text-align: center;
}

/* Tanggal */
.laporan-card th:nth-child(2),
.laporan-card td:nth-child(2) {
    width: 105px;
    min-width: 105px;
    white-space: nowrap;
}

/* Nama Siswa */
.laporan-card th:nth-child(3),
.laporan-card td:nth-child(3) {
    width: 165px;
    min-width: 165px;
}

/* Kelas */
.laporan-card th:nth-child(4),
.laporan-card td:nth-child(4) {
    width: 90px;
    min-width: 90px;
    white-space: nowrap;
}

/* Status */
.laporan-card th:nth-child(5),
.laporan-card td:nth-child(5) {
    width: 95px;
    min-width: 95px;
    text-align: center;
}

/* Terlambat */
.laporan-card th:nth-child(6),
.laporan-card td:nth-child(6) {
    width: 105px;
    min-width: 105px;
    text-align: center;
}

/* Jam Masuk */
.laporan-card th:nth-child(7),
.laporan-card td:nth-child(7) {
    width: 105px;
    min-width: 105px;
    text-align: center;
    white-space: nowrap;
}

/* Keterangan */
.laporan-card th:nth-child(8),
.laporan-card td:nth-child(8) {
    width: 360px;
    min-width: 360px;
    white-space: normal;
    overflow-wrap: break-word;
}

/* Nilai Disiplin */
.laporan-card th:nth-child(9),
.laporan-card td:nth-child(9) {
    width: 110px;
    min-width: 110px;
    text-align: center;
}

@media (max-width: 767.98px) {
    .laporan-card .card-body {
        padding: 16px !important;
    }


    .statistik-print .alert {
        height: 100%;
        margin-bottom: 0;
        border-radius: 12px;
        padding: 18px 20px;
        font-size: 18px;
    }

    .row-cols-md-5 > .col {
        display: flex;
    }

    .row-cols-md-5 .alert {
        flex: 1;
    }
}
</style>
@endsection

@section('content')
<div class="card border-0 shadow-sm laporan-card" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-4">Laporan Absensi</h4>

        <div class="d-flex gap-2 mb-4 no-print d-print-none">
            <a href="{{ route('laporan.absensi-harian') }}"
                class="btn btn-primary">
                 Absensi Harian
            </a>

            <a href="{{ route('laporan.absensi-mapel') }}"
                class="btn btn-outline-primary">
                 Absensi Mapel
            </a>
        </div>

        <div class="d-none d-print-block mb-3">
            <p class="mb-1">
                <strong>Periode :</strong>
                {{ request('tanggal_dari')
                    ? \Carbon\Carbon::parse(request('tanggal_dari'))->locale('id')->translatedFormat('d F Y')
                    : now()->startOfMonth()->locale('id')->translatedFormat('d F Y') }}
                –
                {{ request('tanggal_sampai')
                    ? \Carbon\Carbon::parse(request('tanggal_sampai'))->locale('id')->translatedFormat('d F Y')
                    : now()->endOfMonth()->locale('id')->translatedFormat('d F Y') }}
            </p>
        </div>

        <form method="GET" class="row g-3 mb-4 no-print d-print-none">
            <div class="col-md-3">
                <label class="form-label">Tanggal Dari</label>
                <input type="date" name="tanggal_dari" class="form-control"
                       value="{{ request('tanggal_dari') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Tanggal Sampai</label>
                <input type="date" name="tanggal_sampai" class="form-control"
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

            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    Filter
                </button>

                <a href="{{ route('laporan.absensi-harian') }}"
                    class="btn btn-secondary">
                    Reset
                </a>

                <button type="submit"
                        name="cetak"
                        value="1"
                        class="btn btn-success"
                        formtarget="_blank">
                    Cetak
                </button>

                <a href="{{ route('admin.export-absensi-harian', request()->only([
                    'kelas_id',
                    'tanggal_dari',
                    'tanggal_sampai',
                    'status',
                    'siswa_id'
                ])) }}"
                    class="btn btn-success">
                    Export Excel
                </a>
            </div>
        </form>

        <div class="row row-cols-2 row-cols-md-5 g-3 mb-4 statistik-print">
            <div class="col">
                <div class="alert alert-success h-100 mb-0">
                    Hadir: {{ $statistik['hadir'] }}
                </div>
            </div>

            <div class="col">
                <div class="alert alert-warning h-100 mb-0">
                     Izin: {{ $statistik['izin'] }}
                </div>
            </div>

            <div class="col">
                <div class="alert alert-info h-100 mb-0">
                    Sakit: {{ $statistik['sakit'] }}
                </div>
            </div>

            <div class="col">
                <div class="alert alert-danger h-100 mb-0">
                    Alpha: {{ $statistik['alpha'] }}
                </div>
            </div>

            <div class="col">
                <div class="alert alert-secondary h-100 mb-0">
                    Terlambat: {{ $statistik['terlambat'] }}
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Terlambat</th>
                        <th>Jam Masuk</th>
                        <th>Keterangan</th>
                        <th>Nilai Disiplin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensi as $no => $a)
                        <tr>
                            <td>{{ $no + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $a->siswa->nama_siswa ?? '-' }}</td>
                            <td>{{ $a->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td>{{ $a->status }}</td>
                            <td>
                                @if($a->terlambat)
                                    <span class="badge bg-warning text-dark">Ya</span>
                                @else
                                    <span class="badge bg-success">Tidak</span>
                                @endif
                            </td>
                            <td>{{ $a->jam_masuk ? \Carbon\Carbon::parse($a->jam_masuk)->format('H:i'): '-' }}</td>
                            <td>{{ $a->keterangan ?? '-' }}</td>
                            <td>{{ $a->scan_score ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                Belum ada data absensi harian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($absensi, 'links'))
            <div class="no-print d-print-none">
                {{ $absensi->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
