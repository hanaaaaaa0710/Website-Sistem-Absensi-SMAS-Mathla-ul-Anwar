@extends('layout.master')

@section('title', 'Data Mata Pelajaran')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between mb-3">
            <h4 class="fw-bold">Data Mata Pelajaran</h4>
            <a href="{{ route('mata-pelajaran.create') }}" class="btn btn-primary">
                Tambah Mata Pelajaran
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mapel</th>
                    <th>Kode Mapel</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mataPelajaran as $no => $item)
                    <tr>
                        <td>{{ $mataPelajaran->firstItem() + $no }}</td>
                        <td>{{ $item->nama_mapel ?? '-' }}</td>
                        <td>{{ $item->kode_mapel ?? '-' }}</td>
                        <td>
                            <a href="{{ route('mata-pelajaran.edit', ['mata_pelajaran' => $item->id]) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('mata-pelajaran.destroy', ['mata_pelajaran' => $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
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
                        <td colspan="4" class="text-center">
                            Belum ada data mata pelajaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $mataPelajaran->links() }}
    </div>
</div>
@endsection