@extends('layout.master')
@section('title', 'Detail Guru')

@section('content')
<div class="card border-0 shadow-sm p-4" style="border-radius:18px;">
    <h4>Detail Guru</h4>

    <table class="table table-bordered">
        <tr>
            <th>Nama Guru</th>
            <td>{{ $guru->nama_guru }}</td>
        </tr>
        <tr>
            <th>Mata Pelajaran</th>
            <td>{{ $guru->mataPelajaran->nama_mapel ?? '-' }}</td>
        </tr>
        <tr>
            <th>Kelas Wali</th>

            <td>

                {{ $guru->kelasWali->nama_kelas ?? '-' }}

            </td>
        </tr>

        <tr>
            <th>Tahun Ajaran Wali</th>

            <td>

                {{ $guru->tahun_ajaran_wali ?? '-' }}

            </td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $guru->status }}</td>
        </tr>
        <tr>
            <th>Dibuat Pada</th>
            <td>{{ $guru->created_at->format('d-m-Y H:i') }}</td>
        </tr>
        <tr>
            <th>Diperbarui Pada</th>
            <td>{{ $guru->updated_at->format('d-m-Y H:i') }}</td>
        </tr>
    </table>

    <a href="{{ route('guru.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('guru.edit', $guru->id) }}" class="btn btn-primary">Edit</a>
</div>
@endsection