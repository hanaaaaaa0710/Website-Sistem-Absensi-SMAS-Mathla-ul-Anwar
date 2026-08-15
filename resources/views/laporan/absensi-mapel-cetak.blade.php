<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Mata Pelajaran</title>
    <style>
        body { margin:0; font-family:Arial,sans-serif; color:#000; font-size:10px; }
        .kop-laporan { display:flex; align-items:center; border-bottom:3px solid #000; padding-bottom:8px; margin-bottom:12px; }
        .kop-logo { width:70px; height:70px; object-fit:contain; margin-right:16px; }
        .kop-identitas { flex:1; text-align:center; }
        .kop-identitas h1 { margin:0; font-size:18px; text-transform:uppercase; }
        .kop-identitas h3 { margin:4px 0; font-size:14px; font-weight:normal; }
        .kop-identitas p { margin:2px 0; font-size:10px; }
        .judul-laporan { text-align:center; margin:10px 0 8px; }
        .judul-laporan h2 { margin:0; text-transform:uppercase; font-size:16px; }
        .informasi-laporan { width:100%; margin-bottom:10px; font-size:10px; border-collapse:collapse; }
        .informasi-laporan td { border:none; padding:2px 4px; }
        .statistik { display:flex; justify-content:center; gap:10px; margin-bottom:12px; }
        .statistik span { border:1px solid #777; border-radius:5px; padding:5px 10px; }
        .tabel-laporan { width:100%; border-collapse:collapse; table-layout:fixed; page-break-inside:auto; }
        .tabel-laporan th, .tabel-laporan td { border:1px solid #999; padding:5px; vertical-align:middle; overflow-wrap:break-word; }
        .tabel-laporan th { background:#eee; }
        .tabel-laporan tbody tr:nth-child(odd) { background:#f6f6f6; }
        .tabel-laporan thead { display:table-header-group; }
        .tabel-laporan tbody { display:table-row-group; }
        .tabel-laporan tr { page-break-inside:avoid; break-inside:avoid; page-break-after:auto; }
        .tanda-tangan { width:100%; margin-top:30px; border-collapse:collapse; page-break-inside:avoid; break-inside:avoid; }
        .tanda-tangan td { width:50%; border:none; text-align:center; vertical-align:top; padding:0 30px; }
        .ruang-tanda-tangan { height:60px; }
        .nama-penanda-tangan { font-weight:bold; text-decoration:underline; }
        @page { size:A4 landscape; margin:8mm; }
    </style>
</head>
<body>
    @php
        $kelasDipilih = request('kelas_id') ? $kelas->firstWhere('id', request('kelas_id')) : null;
        $namaKelas = $kelasDipilih->nama_kelas ?? 'Semua Kelas';
        $statusDipilih = request('status') ?: 'Semua Status';
        $siswaDipilih = request('siswa_id') ? $siswa->firstWhere('id', request('siswa_id')) : null;
        $namaSiswa = $siswaDipilih->nama_siswa ?? 'Semua Siswa';
        $mapelDipilih = request('mata_pelajaran_id')
            ? $mataPelajaran->firstWhere('id', request('mata_pelajaran_id'))
            : null;

        $namaMapel = $mapelDipilih->nama_mapel ?? 'Semua Mata Pelajaran';
    @endphp

    <div class="kop-laporan">
        <img src="{{ asset('admin/assets/images/logos/logosma.png') }}" alt="Logo Sekolah" class="kop-logo">
        <div class="kop-identitas">
            <h1>SMAS Mathla'ul Anwar</h1>
            <h3>Sistem Informasi Absensi Siswa</h3>
            <p>Batujaya, Karawang</p>
        </div>
    </div>

    <div class="judul-laporan"><h2>Laporan Absensi Mata Pelajaran</h2></div>

    <table class="informasi-laporan">
        <tr>
            <td style="width:12%;">Periode</td>
            <td style="width:38%;">:
                {{ request('tanggal_dari')
                    ? \Carbon\Carbon::parse(request('tanggal_dari'))
                    ->locale('id')
                    ->translatedFormat('d F Y')
                : now()
                    ->startOfMonth()
                    ->locale('id')
                    ->translatedFormat('d F Y') }}
                -
                {{ request('tanggal_sampai')
                    ? \Carbon\Carbon::parse(request('tanggal_sampai'))
                        ->locale('id')
                        ->translatedFormat('d F Y')
                    : now()
                        ->endOfMonth()
                        ->locale('id')
                        ->translatedFormat('d F Y') }}
            </td>
            <td style="width:12%;">Kelas</td>
            <td style="width:38%;">: {{ $namaKelas }}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>: {{ $statusDipilih }}</td>

            <td>Siswa</td>
            <td>: {{ $namaSiswa }}</td>
        </tr>

        <tr>
            <td>Mata Pelajaran</td>
            <td>: {{ $namaMapel }}</td>

            <td></td>
            <td></td>
        </tr>
    </table>

    <div class="statistik">
        <span>Hadir: {{ $statistik['hadir'] }}</span>
        <span>Izin: {{ $statistik['izin'] }}</span>
        <span>Sakit: {{ $statistik['sakit'] }}</span>
        <span>Alpha: {{ $statistik['alpha'] }}</span>
        <span>Terlambat: {{ $statistik['terlambat'] }}</span>
    </div>

    <table class="tabel-laporan">
        <thead>
            <tr>
                <th style="width:4%">No</th><th style="width:10%">Tanggal</th><th style="width:16%">Nama Siswa</th>
                <th style="width:8%">Kelas</th><th style="width:10%">Jam</th><th style="width:11%"> Mata Pelajaran</th>
                <th style="width:14%">Status</th><th style="width:14%">Catatan Guru</th><th style="width:10%">Nilai Disiplin</th>
            </tr>
        </thead>
        @forelse($absensiMapel as $no => $a)
        <tr>

        <td>{{ $no+1 }}</td>

        <td>
        {{ $a->tanggal
        ? \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y')
        : '-' }}
        </td>

        <td>{{ $a->siswa->nama_siswa ?? '-' }}</td>

        <td>{{ $a->jadwalPelajaran->kelas->nama_kelas ?? '-' }}</td>

        <td>
        {{ \Carbon\Carbon::parse($a->jadwalPelajaran->jam_mulai)->format('H:i') }}
        -
        {{ \Carbon\Carbon::parse($a->jadwalPelajaran->jam_selesai)->format('H:i') }}
        </td>

        <td>{{ $a->jadwalPelajaran->mataPelajaran->nama_mapel ?? '-' }}</td>

        <td>{{ $a->status }}</td>

        <td>{{ $a->catatan ?? '-' }}</td>

        <td>{{ $a->scan_score ?? '-' }}</td>

        </tr>

        @empty

        <tr>
        <td colspan="9" style="text-align:center">
        Belum ada data absensi.
        </td>
        </tr>

        @endforelse
    </table>

    <table class="tanda-tangan">
        <tr>
            <td>
                Mengetahui,<br>Kepala Sekolah
                <div class="ruang-tanda-tangan"></div>
                <div class="nama-penanda-tangan">........................................</div>
                <div>NIP. ................................</div>
            </td>
            <td>
                Karawang, {{ now()->locale('id')->translatedFormat('d F Y') }}<br>Wali Kelas
                <div class="ruang-tanda-tangan"></div>
                <div class="nama-penanda-tangan">........................................</div>
                <div>NIP. ................................</div>
            </td>
        </tr>
    </table>

    <script>
        window.addEventListener('load', function () { window.print(); });
    </script>
</body>
</html>
