@extends('layout.master')

@section('title', 'Data Belum Terhubung')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Data Belum Lengkap</h4>
        <p class="text-muted mb-0">{{ $message ?? 'Data akun belum terhubung.' }}</p>
    </div>
</div>
@endsection