@extends('layout.master')

@section('title', 'Notifikasi Saya')

@section('content')
<style>
    .notif-card {
        transition: all 0.3s ease;
    }

    .notif-highlight {
        border: 2px solid #86b7fe !important;
        background: #eef5ff !important;
        box-shadow: 0 0 0 4px rgba(13,110,253,0.08);
    }
</style>

<div class="card border-0 shadow-sm" style="border-radius:18px;">
    <div class="card-body p-4">
        <h3 class="fw-bold mb-2">Notifikasi Saya</h3>
        <p class="text-muted mb-4">Pesan yang dikirim oleh guru atau sekolah.</p>

        @forelse($notifikasi as $item)
            <div class="border rounded-3 p-3 mb-3 notif-card {{ request('highlight') == $item->id ? 'notif-highlight' : '' }}"
                 id="notif-{{ $item->id }}">

                <h6 class="fw-bold mb-1">{{ $item->judul }}</h6>

                <small class="text-muted">
                    {{ $item->created_at ? $item->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') : '-' }}
                </small>

                <p class="mb-0 mt-2">
                    {{ str_replace(['[nama]', '[Nama Siswa]', '[Indah Ramadhan]'], $siswa->nama_siswa, $item->isi) }}
                </p>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                Belum ada notifikasi.
            </div>
        @endforelse

        @if($notifikasi instanceof \Illuminate\Pagination\LengthAwarePaginator)
            {{ $notifikasi->links() }}
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const highlightId = "{{ request('highlight') }}";

    if (highlightId) {
        const target = document.getElementById('notif-' + highlightId);

        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            setTimeout(() => {
                target.classList.remove('notif-highlight');
            }, 3500);
        }
    }
});
</script>
@endsection