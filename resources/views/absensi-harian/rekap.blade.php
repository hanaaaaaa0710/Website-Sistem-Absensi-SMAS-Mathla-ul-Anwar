@extends('layout.master')
@section('title','Rekap Absensi Harian')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Rekap Absensi Harian</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Siswa</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Nilai Disiplin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $item)
                <tr class="@if($item->status=='Hadir') bg-success text-white
                           @elseif($item->status=='Alpha') bg-danger text-white
                           @elseif($item->status=='Izin') bg-warning
                           @elseif($item->status=='Sakit') bg-info text-white
                           @endif">
                    <td> {{ $item->tanggal? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'): '-' }}</td>
                    <td>{{ optional($item->siswa)->nama_siswa ?? '-' }}</td>
                    <td>{{ $item->status ?? '-' }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>{{ $item->scan_score ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data absensi</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $absensi->links() }}
    </div>
</div>
@endsection