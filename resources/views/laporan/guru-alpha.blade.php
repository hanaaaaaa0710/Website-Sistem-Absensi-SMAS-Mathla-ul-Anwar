@extends('layout.master')

@section('title', 'Laporan Siswa Alpha')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-3">Laporan Siswa Alpha</h3>
        <p class="text-muted">Daftar siswa yang alpha pada mata pelajaran yang diajar.</p>

        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Total Alpha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswaAlpha as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->nama_siswa }}</td>
                        <td>{{ $item->total_alpha }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">
                            Tidak ada siswa alpha.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <a href="{{ route('dashboard') }}" class="btn btn-light border">Kembali</a>
    </div>
</div>
@endsection