@extends('layout.master')
@section('title','Dashboard Siswa')

@section('content')
<h1>Dashboard Orang Tua/Wali</h1>

<p>
    <strong>Orang Tua/Wali:</strong>
    {{ Auth::user()->name }}
</p>

<p>
    <strong>Nama Anak:</strong>
    {{ $siswa->nama_siswa ?? '-' }}
</p>

<p>
    <strong>Kelas:</strong>
    {{ $siswa->kelas->nama_kelas ?? '-' }}
</p>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">

        <h5 class="mb-4 fw-bold text-primary">
            Statistik Kehadiran
        </h5>

        <div class="row g-3">
            <div class="col-md">
                <div class="card border-0 shadow-sm h-100 bg-success-subtle">
                    <div class="card-body text-center py-4">
                        <h6 class="text-success mb-2">Hadir</h6>
                        <h2 class="fw-bold mb-0">
                            {{ $stats['hadir'] ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card border-0 shadow-sm h-100 bg-warning-subtle">
                    <div class="card-body text-center py-4">
                        <h6 class="text-warning mb-2">Izin</h6>
                        <h2 class="fw-bold mb-0">
                            {{ $stats['izin'] ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card border-0 shadow-sm h-100 bg-info-subtle">
                    <div class="card-body text-center py-4">
                        <h6 class="text-info mb-2">Sakit</h6>
                        <h2 class="fw-bold mb-0">
                            {{ $stats['sakit'] ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-md">
                <div class="card border-0 shadow-sm h-100 bg-danger-subtle">
                    <div class="card-body text-center py-4">
                        <h6 class="text-danger mb-2">Alpha</h6>
                        <h2 class="fw-bold mb-0">
                            {{ $stats['alpha'] ?? 0 }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<h3>Grafik Kehadiran Bulanan Tahun {{ now()->year }}</h3>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <canvas id="chartSiswa" height="120"></canvas>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h3 class="mb-3">Riwayat Keterangan Kehadiran</h3>

        <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Nilai Disiplin</th>
                </tr>
            </thead>

            <tbody>
                @forelse(($catatan ?? []) as $c)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td>
                            {{ $c->tanggal
                                ? \Carbon\Carbon::parse($c->tanggal)->format('d-m-Y')
                                : '-' }}
                        </td>

                        <td>
                            @if($c->status == 'Hadir')
                                <span class="badge bg-success">Hadir</span>
                            @elseif($c->status == 'Alpha')
                                <span class="badge bg-danger">Alpha</span>
                            @elseif($c->status == 'Izin')
                                <span class="badge bg-warning text-dark">Izin</span>
                            @elseif($c->status == 'Sakit')
                                <span class="badge bg-info text-dark">Sakit</span>
                            @elseif($c->status == 'Terlambat')
                                <span class="badge bg-primary">Terlambat</span>
                            @else
                                <span class="badge bg-secondary">
                                    {{ $c->status ?? '-' }}
                                </span>
                            @endif
                        </td>

                        <td>
                            {{ $c->keterangan ?? '-' }}
                        </td>

                        <td>{{ $c->scan_score ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Belum ada catatan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('chartSiswa');

    if (!el) return;

    new Chart(el, {
        type: 'bar',
        data: {
            labels: {!! json_encode($monthly['tanggal'] ?? []) !!},
            datasets: [
                {
                    label: 'Hadir',
                    data: {!! json_encode($monthly['hadir'] ?? []) !!}
                },
                {
                    label: 'Alpha',
                    data: {!! json_encode($monthly['alpha'] ?? []) !!}
                },
                {
                    label: 'Izin',
                    data: {!! json_encode($monthly['izin'] ?? []) !!}
                },
                {
                    label: 'Sakit',
                    data: {!! json_encode($monthly['sakit'] ?? []) !!}
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
});
</script>

<style>
.table th{
    white-space: nowrap;
    text-align:center;
}

.table td{
    vertical-align: middle;
}

.table{
    min-width:750px;
}
</style>
@endsection
