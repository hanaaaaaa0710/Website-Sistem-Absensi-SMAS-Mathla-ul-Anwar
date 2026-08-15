@extends('layout.master')
@section('title','Rekap Absensi Mapel')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Rekap Absensi Mapel</h5>
        <form method="GET" action="{{ route('guru.absensi-mapel.rekap') }}" class="mb-3">
            <select name="jadwal_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Semua Kelas --</option>

                @foreach($jadwalList as $jadwal)
                    <option value="{{ $jadwal->id }}" {{ $jadwalId == $jadwal->id ? 'selected' : '' }}>
                        {{ $jadwal->kelas->nama_kelas ?? '-' }} - {{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}
                    </option>
                @endforeach
            </select>
        </form>

        <div class="mb-3">
            <a href="{{ route('guru.absensi-mapel.download', ['jadwal_id' => request('jadwal_id')]) }}" class="btn btn-success">
                Download Rekap
            </a>

            <a href="{{ route('guru.absensi-mapel.cetak', ['jadwal_id' => request('jadwal_id')]) }}" target="_blank" class="btn btn-primary">
                Cetak Rekap
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" style="min-width:950px;">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Catatan Guru (Opsional)</th>
                        <th>Nilai Disiplin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensiMapel as $item)
                    <tr class="@if($item->status=='Hadir') bg-success text-white
                            @elseif($item->status=='Alpha') bg-danger text-white
                            @elseif($item->status=='Izin') bg-warning
                            @elseif($item->status=='Sakit') bg-info text-white
                            @endif">
                        <td>{{ $item->tanggal? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'): '-' }}</td>
                        <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
                        <td>{{ optional(optional($item->jadwalPelajaran)->kelas)->nama_kelas ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->jadwalPelajaran->jam_mulai)->format('H:i') }}
                                -
                            {{ \Carbon\Carbon::parse($item->jadwalPelajaran->jam_selesai)->format('H:i') }}
                        </td>
                        <td>{{ $item->jadwalPelajaran->mataPelajaran->nama_mapel ?? '-' }}</td>
                        <td>{{ $item->status ?? '-' }}</td>
                        <td>{{ $item->catatan ?? '-' }}</td>
                        <td>{{ $item->scan_score ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data absensi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $absensiMapel->links() }}
    </div>
</div>
@endsection