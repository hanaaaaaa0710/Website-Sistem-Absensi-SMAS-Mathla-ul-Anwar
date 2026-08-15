@extends('layout.master')
@section('title','Absensi Mapel')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Absensi Mapel</h5>
        <a href="{{ route('absensi-mapel.create') }}" class="btn btn-primary mb-3">Tambah Absensi</a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Siswa</th>
                    <th>Mata Pelajaran</th>
                    <th>Status</th>
                    <th>Catatan Guru (Opsional)</th>
                    <th>Nilai Disiplin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($absensiMapel as $item)
                <tr class="@if($item->status=='Hadir') bg-success text-white
                           @elseif($item->status=='Alpha') bg-danger text-white
                           @elseif($item->status=='Izin') bg-warning
                           @elseif($item->status=='Sakit') bg-info text-white
                           @endif">
                    <td>{{ $item->siswa->nama }}</td>
                    <td>{{ optional(optional($item->jadwalPelajaran)->mataPelajaran)->nama_mapel ?? '-' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                    <td>{{ $item->scan_score ?? '-' }}</td>
                    <td>
                        <a href="{{ route('absensi-mapel.edit',$item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('absensi-mapel.destroy',$item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus absensi?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $absensiMapel->links() }}
    </div>
</div>
@endsection