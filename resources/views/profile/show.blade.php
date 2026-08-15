@extends('layout.master')

@section('title', 'Profil Akun')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-3">Profil Akun</h4>

        <p>
            <strong>Nama:</strong>
            {{ $user->name ?? '-' }}
        </p>

        <p>
            <strong>Email:</strong>
            {{ $user->email ?? '-' }}
        </p>

        <p>
            <strong>Role:</strong>
            {{ ucwords(str_replace('_', ' ', $user->role ?? '-')) }}
        </p>

        <p>
            <strong>Status Akun:</strong>
            {{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}
        </p>
    </div>
</div>
@endsection