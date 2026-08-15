@extends('layout.master')
@section('title','Rekap Absensi Mapel per Kelas')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Rekap Absensi Mapel @if($kelasId) - Kelas {{ optional($kelasList->where('id',$kelasId)->first())->nama_kelas }} @endif</h5>

        {{-- Dropdown filter kelas --}}
        <form method="GET" action="{{ route('guru.absensi-mapel.rekap-perkelas') }}" class="mb-3">
            
            <div class="row g-2">
                <div class="col-md-4">
                    <label>Kelas</label>
                    <select name="kelas_id" class="form-control">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id }}" {{ ($kelasId == $k->id) ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ $tanggalMulai }}">
                </div>
                <div class="col-md-3">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ $tanggalSelesai }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped" style="min-width:850px;">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Jam</th>
                        <th>Mata Pelajaran</th>
                        <th>Status</th>
                        <th>Catatan Guru (Opsional)</th>
                        <th>Nilai Disiplin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($absensiMapel as $item)
                    <tr class="@if($item->status=='Hadir') bg-success text-white
                                @elseif($item->status=='Alpha') bg-danger text-white
                                @elseif($item->status=='Izin') bg-warning
                                @elseif($item->status=='Sakit') bg-info text-white
                                @endif">
                        <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y'): '-' }}</td>
                        <td>{{ optional($item->siswa)->nama_siswa ?? '-' }}</td>
                        <td>
                            {{ optional(optional($item->jadwalPelajaran)->jam_mulai)
                                ? \Carbon\Carbon::parse($item->jadwalPelajaran->jam_mulai)->format('H:i')
                                : '-' }}
                            -
                            {{ optional(optional($item->jadwalPelajaran)->jam_selesai)
                                ? \Carbon\Carbon::parse($item->jadwalPelajaran->jam_selesai)->format('H:i')
                                : '-' }}
                        </td>
                        <td>{{ optional(optional($item->jadwalPelajaran)->mataPelajaran)->nama_mapel ?? '-' }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->catatan ?? '-' }}</td>
                        <td>{{ $item->scan_score ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection