@extends('layout.master')
@section('title','Dashboard Admin')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card p-3">
            <h5>Total Siswa</h5>
            <h3>{{ $totalSiswa }}</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <h5>Total Guru</h5>
            <h3>{{ $totalGuru }}</h3>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-body">
        <h5>Rekap Absensi Harian Terbaru</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Siswa</th><th>Tanggal</th><th>Status</th><th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensiHarian as $a)
                <tr>
                    <td>{{ $a->siswa->nama_siswa ?? '-' }}</td>
                    <td>{{ $a->tanggal->format('d-m-Y') }}</td>
                    <td>{{ $a->status }}</td>
                    <td>{{ $a->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">Belum ada data absensi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection