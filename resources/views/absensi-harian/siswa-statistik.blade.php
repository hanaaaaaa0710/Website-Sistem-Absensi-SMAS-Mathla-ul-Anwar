@extends('layout.master')

@section('title', 'Statistik Siswa')

@section('content')
<div class="pt-3">
    <div class="card border-0 shadow-sm" style="border-radius:18px;">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-1">Statistik Siswa</h3>
            <p class="text-muted mb-4">Ringkasan kehadiran siswa bulan ini.</p>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <p class="text-muted mb-1">Hadir</p>
                            <h3 class="text-success mb-0">{{ $hadir ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <p class="text-muted mb-1">Izin</p>
                            <h3 class="text-warning mb-0">{{ $izin ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <p class="text-muted mb-1">Sakit</p>
                            <h3 class="text-danger mb-0">{{ $sakit ?? 0 }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <p class="text-muted mb-1">Alpha</p>
                            <h3 class="text-danger mb-0">{{ $alpha ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="fw-bold mb-3">Persentase Kehadiran</h5>
            <div class="progress" style="height: 18px;">
                <div class="progress-bar" style="width: {{ $persentase ?? 0 }}%;">
                    {{ $persentase ?? 0 }}%
                </div>
            </div>
        </div>
    </div>
</div>
@endsection