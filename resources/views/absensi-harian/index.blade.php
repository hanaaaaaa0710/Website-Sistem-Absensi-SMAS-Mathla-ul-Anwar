@extends('layout.master')

@section('title','Rekap Absensi Harian')

@section('content')

<div class="alert alert-info">
    Data absensi harian dibuat otomatis berdasarkan hasil rekapitulasi absensi mata pelajaran pada tanggal tersebut.
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Rekap Absensi Harian</h5>

        <form method="GET" action="{{ route('absensi-harian.index') }}" class="mb-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Filter Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('absensi-harian.index') }}" class="btn btn-light border">Reset</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Status</th>
                    <th>Terlambat</th>
                    <th>Nilai Disiplin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $no => $a)
                    <tr>
                        <td>{{ $absensi->firstItem() + $no }}</td>
                        <td>{{ $a->siswa->nama_siswa ?? '-' }}</td>
                        <td>
                            {{ $a->tanggal
                                ? \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y')
                                : '-' }}
                        </td>
                        <td>{{ $a->jam_masuk ?? '-' }}</td>
                        <td>{{ $a->status ?? '-' }}</td>
                        <td>
                            @if($a->terlambat)
                                <span class="badge bg-warning text-dark">Ya</span>
                            @else
                                <span class="badge bg-success">Tidak</span>
                            @endif
                        </td>
                        <td>{{ $a->scan_score ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada absensi mata pelajaran yang dicatat hari ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $absensi->links() }}
    </div>
</div>
@endsection