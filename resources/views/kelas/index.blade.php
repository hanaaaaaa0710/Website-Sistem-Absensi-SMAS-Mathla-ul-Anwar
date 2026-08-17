@extends('layout.master')
@section('title', 'Data Kelas')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 18px;">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h4 class="mb-0">Data Kelas</h4>

            <a href="{{ route('kelas.create') }}" class="btn btn-primary">
                Tambah Kelas
            </a>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($kelas as $no => $k)
                    <tr>
                        <td>{{ $kelas->firstItem() + $no }}</td>
                        <td>{{ $k->nama_kelas }}</td>
                        <td>
                            <a href="{{ route('kelas.edit', $k->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('kelas.destroy', $k->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Hapus kelas ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">
                            Belum ada data kelas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $kelas->links() }}
    </div>
</div>
@endsection