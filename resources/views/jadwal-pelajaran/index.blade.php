@extends('layout.master')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between mb-3">
            <h4 class="fw-bold">Jadwal Pelajaran</h4>
            <a href="{{ route('jadwal-pelajaran.create') }}" class="btn btn-primary">
                Tambah Jadwal
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Guru</th>
                    <th>Hari</th>
                    <th>Jam</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwal as $no => $item)
                    <tr>
                        <td>{{ $jadwal->firstItem() + $no }}</td>
                        <td>{{ $item->kelas->nama_kelas ?? '-' }}</td>
                        <td>{{ $item->mataPelajaran->nama_mapel ?? '-' }}</td>
                        <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                        <td>{{ $item->hari ?? '-' }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} 
                        </td>
                        <td>
                            <a href="{{ route('jadwal-pelajaran.edit', ['jadwal_pelajaran' => $item->id]) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('jadwal-pelajaran.destroy', ['jadwal_pelajaran' => $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            Belum ada data jadwal pelajaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $jadwal->links() }}
    </div>
</div>
@endsection