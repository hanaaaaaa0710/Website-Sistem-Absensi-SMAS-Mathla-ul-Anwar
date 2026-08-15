@extends('layout.master')
@section('title', isset($absensiMapel) ? 'Edit Absensi Mapel' : 'Tambah Absensi Mapel')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">{{ isset($absensiMapel) ? 'Edit Absensi Mapel' : 'Tambah Absensi Mapel' }}</h5>

        <form action="{{ isset($absensiMapel) ? route('absensi-mapel.update',$absensiMapel->id) : route('absensi-mapel.store') }}" method="POST">
            @csrf
            @if(isset($absensiMapel))
                @method('PUT')
            @endif

            <div class="mb-3">
                <label>Siswa</label>
                <select name="siswa_id" class="form-control" required>
                    @foreach($siswa as $item)
                        <option value="{{ $item->id }}" {{ (isset($absensiMapel) && $absensiMapel->siswa_id==$item->id) ? 'selected' : '' }}>
                            {{ $item->nama_siswa ?? '-' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Mata Pelajaran</label>
                <select name="jadwal_pelajaran_id" class="form-control" required>
                    @foreach($mapel as $m)
                        <option value="{{ $m->id }}" {{ (isset($absensiMapel) && $absensiMapel->jadwal_pelajaran_id==$m->id) ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $absensiMapel->tanggal ?? old('tanggal') }}" required>
            </div>

            <div class="mb-3">
                <label>Status</label>

                <select name="status" id="statusAbsensi" class="form-control" required>
                    @foreach(['Hadir','Terlambat','Izin','Sakit','Alpha'] as $status)
                        <option value="{{ $status }}" 
                            {{ old('status', $absensiMapel->status ?? '') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="catatan" id="catatanAbsensi" class="form-control"
                    rows="3">{{ old('catatan', $absensiMapel->catatan ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label>Scan Score</label>
                <input type="number" name="scan_score" class="form-control" min="0" max="100" value="{{ $absensiMapel->scan_score ?? old('scan_score') }}">
            </div>

            
            <button type="submit" class="btn btn-primary">{{ isset($absensiMapel) ? 'Update' : 'Simpan' }}</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusInput = document.getElementById('statusAbsensi');
    const catatanInput = document.getElementById('catatanAbsensi');

    if (!statusInput || !catatanInput) {
        return;
    }

    const templateCatatan = {
        Hadir: 'Pertahankan kedisiplinan dan kehadiran yang baik.',
        Terlambat: 'Mohon datang lebih awal agar tidak terlambat kembali.',
        Izin: 'Tetap ikuti materi dan tugas yang tertinggal.',
        Sakit: 'Semoga lekas pulih dan segera mengejar materi yang tertinggal.',
        Alpha: 'Mohon tidak mengulangi ketidakhadiran tanpa keterangan.'
    };

    statusInput.addEventListener('change', function () {
        catatanInput.value = templateCatatan[this.value] ?? '';
    });

    // Untuk form tambah, isi catatan otomatis dari status awal
    @if(!isset($absensiMapel) && !old('catatan'))
        catatanInput.value = templateCatatan[statusInput.value] ?? '';
    @endif
});
</script>
@endsection