@extends('layout.master')

@section('title', 'Rekap Kehadiran Wali Kelas')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-2">Rekap Kehadiran - {{ $kelas->nama_kelas ?? '-' }}</h3>
        <p class="text-muted">Bulan: {{ $bulan }} / {{ $tahun }}</p>

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Hadir</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Alpha</th>
                    <th>Terlambat</th>
                    <th>Total</th>
                    <th>Persentase</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $no => $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item['siswa']->nama_siswa ?? '-' }}</td>
                        <td>{{ $item['hadir'] }}</td>
                        <td>{{ $item['izin'] }}</td>
                        <td>{{ $item['sakit'] }}</td>
                        <td>{{ $item['alpha'] }}</td>
                        <td>{{ $item['terlambat'] }}</td>
                        <td>{{ $item['total'] }}</td>
                        <td>
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
@endsection