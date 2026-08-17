@extends('layout.master')

@section('title', 'Siswa Alpha Wali Kelas')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-2">
            Siswa Alpha - {{ $kelas->nama_kelas ?? '-' }}
        </h3>

        <p class="text-muted mb-4">
            Bulan: {{ $bulan }} / {{ $tahun }}
        </p>

        <div class="table-responsive mb-3">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center" style="width:70px;">No</th>
                        <th>Nama Siswa</th>
                        <th class="text-center" style="width:160px;">Total Alpha</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($siswaAlpha as $no => $item)
                        <tr>
                            <td class="text-center">{{ $no + 1 }}</td>

                            <td>
                                {{ $item->nama_siswa }}
                            </td>

                            <td class="text-center">
                                <span class="badge bg-danger px-3 py-2">
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
        </div>

        <a href="{{ route('dashboard') }}"
           class="btn btn-light border">
            Kembali
        </a>
    </div>
</div>
@endsection