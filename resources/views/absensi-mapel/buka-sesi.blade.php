@extends('layout.master')
@section('title','Buka Sesi Absensi')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Buka Sesi Absensi: {{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}</h5>
        <p class="text-muted">Tanggal: {{ $tanggal }}</p>

        <form action="{{ route('guru.absensi-mapel.simpan-sesi', $jadwal->id) }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Nilai Disiplin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_siswa ?? $item->nama ?? '-' }}</td>
                        <td>
                            <input type="hidden" name="siswa_id[]" value="{{ $item->id }}">

                            @php
                                $statusLama = $absensiLama[$item->id]->status ?? 'Hadir';
                            @endphp

                            <select name="status[{{ $item->id }}]" class="form-control status-absensi" data-siswa="{{ $item->id }}" required>
                                <option value="Hadir" {{ $statusLama == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                                <option value="Alpha" {{ $statusLama == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                                <option value="Izin" {{ $statusLama == 'Izin' ? 'selected' : '' }}>Izin</option>
                                <option value="Sakit" {{ $statusLama == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                                <option value="Terlambat" {{ $statusLama == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                            </select>
                        </td>
                        <td>
                            <input type="text"
                                    name="catatan[{{ $item->id }}]"
                                    class="form-control"
                                    placeholder="Catatan (opsional)"
                                    value="{{ old('catatan.' . $item->id, $absensiLama[$item->id]->keterangan ?? '') }}">
                        </td>
                        <td>
                            <input type="number"
                                    name="scan_score[{{ $item->id }}]"
                                    class="form-control nilai-disiplin"
                                    value="{{ old('scan_score.' . $item->id, $absensiLama[$item->id]->scan_score ?? 100) }}"readonly
                                    style="background-color:#f8f9fa;">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada siswa di kelas ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nilaiMap = {
        Hadir: 100,
        Terlambat: 75,
        Izin: 60,
        Sakit: 60,
        Alpha: 0
    };

    const templateCatatan = {
        Hadir: 'Pertahankan kedisiplinan dan kehadiran yang baik.',
        Terlambat: 'Mohon datang lebih awal agar tidak terlambat kembali.',
        Izin: 'Tetap ikuti materi dan tugas yang tertinggal.',
        Sakit: 'Semoga lekas pulih dan segera mengejar materi yang tertinggal.',
        Alpha: 'Mohon tidak mengulangi ketidakhadiran tanpa keterangan.'
    };

    document.querySelectorAll('.status-absensi').forEach(function (select) {
        const siswaId = select.dataset.siswa;

        const nilaiInput = document.querySelector(
            `input[name="scan_score[${siswaId}]"]`
        );

        const catatanInput = document.querySelector(
            `input[name="catatan[${siswaId}]"]`
        );

        function updateForm(ubahCatatanPaksa = false) {
            const status = select.value;

            // Nilai disiplin otomatis
            if (nilaiInput) {
                nilaiInput.value = nilaiMap[status] ?? 0;
            }

            // Catatan otomatis
            if (catatanInput) {
                const catatanOtomatis = templateCatatan[status] ?? '';

                if (ubahCatatanPaksa || catatanInput.value.trim() === '') {
                    catatanInput.value = catatanOtomatis;
                }
            }
        }

        // Isi otomatis saat halaman dibuka
        updateForm(false);

        // Perbarui saat status diganti
        select.addEventListener('change', function () {
            updateForm(true);
        });
    });
});
</script>
@endsection