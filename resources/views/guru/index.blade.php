@extends('layout.master')
@section('title', 'Data Guru')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Data Guru</h4>
            <a href="{{ route('guru.create') }}" class="btn btn-primary">Tambah Guru</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Guru</th>
                    <th>Mata Pelajaran</th>
                    <th>Kelas Wali</th>
                    <th>Tahun Ajaran</th>
                    <th>Wali Kelas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data_guru as $no => $guru)
                    <tr>
                        <td>{{ $data_guru->firstItem() + $no }}</td>

                        <td>{{ $guru->nama_guru ?? '-' }}</td>

                        <td>
                            {{ $guru->mataPelajaran->nama_mapel ?? '-' }}
                        </td>

                        <td>
                            @if($guru->kelasWali)
                                <span class="badge bg-info text-dark">
                                    {{ $guru->kelasWali->nama_kelas }}
                                </span>
                            @else
                                 -
                            @endif
                        </td>

                        <td>
                            {{ $guru->tahun_ajaran_wali ?? '-' }}
                        </td>

                        <td>
                            @if($guru->kelasWali)
                                <span class="badge bg-primary">
                                    Ya
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    Tidak
                                </span>
                            @endif
                        </td>

                        <td>
                            @if($guru->status === 'Aktif')
                                <span class="badge bg-success">
                                    Aktif
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a
                                    href="{{ route('guru.edit', $guru->id) }}"
                                    class="btn btn-warning btn-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('guru.destroy', $guru->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus data ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            Belum ada data guru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $data_guru->appends(request()->except('guru_page'))->links() }}
    </div>
</div>
@endsection