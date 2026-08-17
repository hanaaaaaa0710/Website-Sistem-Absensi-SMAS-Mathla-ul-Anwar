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


    <div class="table-responsive mb-3">
        <table class="table table-bordered align-middle" style="min-width: 1000px;">
            <thead>
                <tr>
                    <th class="text-center" style="width:70px;">No</th>
                    <th style="min-width:160px;">Nama Guru</th>
                    <th style="min-width:150px;">Mata Pelajaran</th>
                    <th tyle="min-width:130px;">Kelas Wali</th>
                    <th class="text-center" style="min-width:120px;">Tahun Ajaran</th>
                    <th class="text-center" style="min-width:110px;">Wali Kelas</th>
                    <th class="text-center" style="min-width:110px;">Status</th>
                    <th class="text-center" style="min-width:150px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data_guru as $no => $guru)
                    <tr>
                        <td class="text-center">
                            {{ $data_guru->firstItem() + $no }}</td>

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

                        <td class="text-center">
                            {{ $guru->tahun_ajaran_wali ?? '-' }}
                        </td>

                        <td class="text-center">
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

                        <td class="text-center">
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

                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2 flex-nowrap">
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
</div>

<style>
    .table-responsive {
        width: 100%;
        max-width: 100%;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch;
    }

    .table th,
    .table td {
        vertical-align: middle;
    }

    @media (max-width: 575.98px) {
        .card-body {
            padding: 16px !important;
        }

        .d-flex.justify-content-between.align-items-center {
            gap: 12px;
            flex-wrap: wrap;
        }
    }
</style>
@endsection