@extends('layout.master')

@section('title', 'Jadwal Saya')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-3">Jadwal Saya</h4>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Ruang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $item)
                    <tr>
                        <td>{{ $item->hari }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                        </td>   
                        <td>{{ $item->mataPelajaran->nama_mapel ?? '-' }}</td>
                        <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                        <td>{{ $item->ruang_kelas ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Jadwal belum tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection