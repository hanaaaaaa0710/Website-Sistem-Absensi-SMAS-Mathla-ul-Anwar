@extends('layout.master')
@section('title','Dashboard Guru')

@section('content')
<h1>Dashboard Guru</h1>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">

        <h5 class="mb-4 fw-bold text-primary">
            Statistik Absensi
        </h5>

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
        </div>

    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Jadwal Mengajar Hari Ini</h5>

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas</th>
                    <th>Jam</th>
                    <th>Ruangan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($jadwalHariIni as $jadwal)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $jadwal->mataPelajaran->nama_mapel }}</td>
                        <td>{{ $jadwal->kelas->nama_kelas }}</td>
                        <td>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                        <td>{{ $jadwal->ruang_kelas ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Tidak ada jadwal mengajar hari ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>Absensi Mapel Terbaru</h5>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th><th>Siswa</th><th>Kelas</th><th>Mata Pelajaran</th><th>Status</th><th>Catatan Guru (Opsional)</th><th>Nilai Disiplin</th><th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($absensiMapel ?? []) as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ $item->jadwalPelajaran->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $item->jadwalPelajaran->mataPelajaran->nama_mapel ?? '-' }}</td>
                        <td>{{ $item->status ?? '-' }}</td>
                        <td>{{ $item->catatan ?? $item->keterangan ?? '-' }}</td>
                        <td>{{ $item->scan_score ?? '-' }}</td>
                        <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">Belum ada data absensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection