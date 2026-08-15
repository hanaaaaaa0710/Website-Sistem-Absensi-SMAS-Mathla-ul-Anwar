@extends('layout.master')
@section('title','Edit Absensi')

@section('content')
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Edit Absensi: {{ $jadwal->mataPelajaran->nama_mapel ?? '-' }}</h5>
        <p class="text-muted">Tanggal: {{ $tanggal }}</p>

        <form action="{{ route('guru.absensi-mapel.update-sesi', $jadwal->id) }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Status</th>
                        <th>Catatan Guru (Opsional)</th>
                        <th>Nilai Disiplin</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $index => $item)
                        @php
                            $absen = $absensiLama[$item->id] ?? null;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->nama_siswa ?? '-' }}</td>
                            <td>
                                <input type="hidden" name="siswa_id[]" value="{{ $item->id }}">

                                <select name="status[{{ $item->id }}]" class="form-control status-absensi" data-siswa="{{ $item->id }}" required>
                                    @foreach(['Hadir','Alpha','Izin','Sakit','Terlambat'] as $status)
                                        <option value="{{ $status }}"
                                            {{ ($absen && $absen->status == $status) ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text"
                                        name="catatan[{{ $item->id }}]"
                                        class="form-control catatan-absensi"
                                        data-siswa="{{ $item->id }}"
                                        value="{{ old(
                                            'catatan.' . $item->id, $absen->catatan ?? '') }}"
                                        placeholder="Catatan (opsional)">
                            </td>
                            <td>
                                <input type="number"
                                       name="scan_score[{{ $item->id }}]"
                                       class="form-control nilai-disiplin"
                                       value="{{ old('scan_score.' . $item->id, $absen->scan_score ?? 100) }}"readonly
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
                <button type="submit" class="btn btn-warning">Update Absensi</button>
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

    document.querySelectorAll('.status-absensi').forEach(function (select) {
        const siswaId = select.dataset.siswa;

        const nilaiInput = document.querySelector(
            `input[name="scan_score[${siswaId}]"]`
        );

        const catatanInput = document.querySelector(
            `input[name="catatan[${siswaId}]"]`
        );

        function updateForm(statusBerubah = false) {
            const status = select.value;

            if (nilaiInput) {
                nilaiInput.value = nilaiMap[status] ?? 0;
            }

            if (!catatanInput) {
                return;
            }

            const catatanSekarang = catatanInput.value.trim();
            const masihTemplate = semuaTemplate.includes(catatanSekarang);

            /*
             * Catatan diubah otomatis apabila:
             * 1. Input masih kosong; atau
             * 2. Status berubah dan catatan sebelumnya merupakan template.
             *
             * Catatan khusus yang diketik guru tidak akan ditimpa.
             */
            if (
                catatanSekarang === '' ||
                (statusBerubah && masihTemplate)
            ) {
                catatanInput.value = templateCatatan[status] ?? '';
            }
        }

        // Mengisi nilai dan template apabila catatan lama masih kosong.
        updateForm(false);

        select.addEventListener('change', function () {
            updateForm(true);
        });
    });
});
</script>
@endsection