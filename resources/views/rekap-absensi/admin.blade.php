@extends('layout.master')
@section('title','Rekap Absensi Admin')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="fw-bold mb-3">Rekap Absensi Per Kelas / Mata Pelajaran</h4>

        <form method="GET" action="{{ route('admin.rekap-absensi') }}" class="mb-3">
            <div class="row g-2">
                <div class="col">
                    <select name="kelas_id" class="form-select">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($listKelas as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <select name="mata_pelajaran_id" class="form-select">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($listMapel as $mapel)
                            <option value="{{ $mapel->id }}" {{ request('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>{{ $mapel->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <button class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.rekap-absensi.export', request()->all()) }}" class="btn btn-success">Export Excel</a>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                    <th>Nilai Disiplin</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absensi as $item)
                <tr>
                    <td>{{ $item->siswa->nama ?? '-' }}</td>
                    <td>{{ optional($item->siswa->kelas)->nama_kelas ?? $item->siswa->kelas ?? '-' }}</td>
                    <td>{{ optional($item->jadwalPelajaran->mataPelajaran)->nama_mapel ?? '-' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->keterangan ?? '-' }}</td>
                    <td>{{ $item->scan_score ?? '-' }}</td>
                    <td>{{ $item->tanggal->format('d-m-Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">Belum ada data absensi</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $absensi->links() }}
    </div>
</div>
@endsection