@extends('layout.master')

@section('title', 'Siswa Alpha Wali Kelas')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-2">Siswa Alpha - {{ $kelas->nama_kelas ?? '-' }}</h3>
        <p class="text-muted">Bulan: {{ $bulan }} / {{ $tahun }}</p>

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
                        <td>
                            <span class="badge bg-danger">
                                {{ $item->total_alpha }}
                            </span>
                        </td>
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