@extends('layout.master')

@section('title', 'Profil Siswa')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-3">Profil Siswa</h4>

        <p><strong>Nama:</strong> {{ $siswa->nama_siswa }}</p>
        <p><strong>Jenis Kelamin:</strong> {{ $siswa->jenis_kelamin }}</p>
        <p><strong>TTL:</strong> {{ $siswa->ttl }}</p>
        <p><strong>Kelas:</strong>{{ $siswa->kelas->nama_kelas ?? '-' }}</p>
        <p><strong>Status:</strong> {{ $siswa->status }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
    </div>
</div>
@endsection