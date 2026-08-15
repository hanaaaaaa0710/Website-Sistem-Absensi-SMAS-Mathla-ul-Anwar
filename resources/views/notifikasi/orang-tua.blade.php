@extends('layout.master')

@section('title', 'Notifikasi Orang Tua/Wali')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 18px;">
    <div class="card-body p-4">

        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
            <div>
                <h3 class="fw-bold mb-1">Notifikasi Orang Tua/Wali</h3>
                <p class="text-muted mb-0">
                    Informasi sekolah terkait kehadiran dan perkembangan anak.
                </p>
            </div>
        </div>

        <div class="card border-0 bg-light mb-4" style="border-radius: 14px;">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Nama Anak</small>
                        <strong>{{ $siswa->nama_siswa ?? '-' }}</strong>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Kelas</small>
                        <strong>{{ $siswa->kelas->nama_kelas ?? '-' }}</strong>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted d-block">Orang Tua/Wali</small>
                        <strong>
                            {{ $siswa->nama_ortu ?? auth()->user()->name ?? '-' }}
                        </strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            @forelse($notifikasi as $item)
                <div class="col-12">
                    <div
                        id="notifikasi-{{ $item->id }}"
                        class="card border-0 shadow-sm
                            {{ request('highlight') == $item->id
                                ? 'border border-primary'
                                : '' }}"
                        style="border-radius: 16px;"
                    >
                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        {{ $item->judul }}
                                    </h5>

                                    <small class="text-muted">
                                        {{ $item->created_at
                                            ? $item->created_at
                                                ->timezone('Asia/Jakarta')
                                                ->format('d M Y, H:i')
                                            : '-' }}
                                        WIB
                                    </small>
                                </div>

                                <div>
                                    @if($item->tipe === 'warning')
                                        <span class="badge bg-warning text-dark">
                                            Peringatan
                                        </span>
                                    @elseif($item->tipe === 'success')
                                        <span class="badge bg-success">
                                            Prestasi
                                        </span>
                                    @elseif($item->tipe === 'danger')
                                        <span class="badge bg-danger">
                                            Penting
                                        </span>
                                    @else
                                        <span class="badge bg-info text-dark">
                                            Informasi
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div
                                class="p-3 bg-light"
                                style="border-radius: 12px; white-space: pre-line;"
                            >
                                {{ $item->isi }}
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    @if($item->sudah_dibaca)
                                        <span class="text-success small">
                                            <i class="ti ti-circle-check me-1"></i>
                                            Sudah dibaca
                                        </span>
                                    @else
                                        <span class="text-warning small">
                                            <i class="ti ti-clock me-1"></i>
                                            Belum dibaca
                                        </span>
                                    @endif
                                </div>

                                @if(!$item->sudah_dibaca)
                                    <form
                                        action="{{ route('notifikasi.mark-read', $item->id) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PUT')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-primary"
                                        >
                                            Tandai Sudah Dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="ti ti-bell-off fs-1 text-muted"></i>

                        <h5 class="fw-bold mt-3">
                            Belum Ada Notifikasi
                        </h5>

                        <p class="text-muted mb-0">
                            Informasi dari sekolah akan tampil di halaman ini.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        @if(method_exists($notifikasi, 'hasPages') && $notifikasi->hasPages())
            <div class="mt-4">
                {{ $notifikasi->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
</div>
@endsection

@section('scripts')
@if(request('highlight'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    const target = document.getElementById(
        'notifikasi-{{ request('highlight') }}'
    );

    if (target) {
        target.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
});
</script>
@endif
@endsection