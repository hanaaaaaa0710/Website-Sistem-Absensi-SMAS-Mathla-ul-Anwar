@extends('layout.master')

@section('title', 'Rekap Kehadiran Wali Kelas')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-2">Rekap Kehadiran - {{ $kelas->nama_kelas ?? '-' }}</h3>
        <p class="text-muted">Bulan: {{ $bulan }} / {{ $tahun }}</p>

    <div class="table-responsive mb-3">
        <table class="table table-bordered align-middle" style="min-width: 950px;">
            <thead class="table-dark">
                <tr>
                    <th class="text-center" style="width:70px;">No</th>
                    <th class="text-center">Nama Siswa</th>
                    <th class="text-center">Hadir</th>
                    <th class="text-center">Izin</th>
                    <th class="text-center">Sakit</th>
                    <th class="text-center">Alpha</th>
                    <th class="text-center" style="min-width:110px;">Terlambat</th>
                    <th class="text-center">Total</th>
                    <th class="text-center" style="min-width:120px;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $no => $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $item['siswa']->nama_siswa ?? '-' }}</td>
                        <td class="text-center">{{ $item['hadir'] }}</td>
                        <td class="text-center">{{ $item['izin'] }}</td>
                        <td class="text-center">{{ $item['sakit'] }}</td>
                        <td class="text-center">{{ $item['alpha'] }}</td>
                        <td class="text-center">{{ $item['terlambat'] }}</td>
                        <td class="text-center">{{ $item['total'] }}</td>
                        <td class="text-center">
                            @if($item['persentase'] < 50)
                                <span class="badge bg-danger">{{ $item['persentase'] }}%</span>
                            @elseif($item['persentase'] < 80)
                                <span class="badge bg-warning">{{ $item['persentase'] }}%</span>
                            @else
                                <span class="badge bg-success">{{ $item['persentase'] }}%</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            Belum ada data rekap kehadiran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('dashboard') }}" class="btn btn-light border">Kembali</a>
    </div>
    </div>
</div>

<style>
    .table-responsive {
        width: 100%;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    @media (max-width: 575.98px) {
        .card-body {
            padding: 16px !important;
        }
    }
</style>
@endsection