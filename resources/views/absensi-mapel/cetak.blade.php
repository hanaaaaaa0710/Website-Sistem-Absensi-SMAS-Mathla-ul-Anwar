<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rekap Absensi Mata Pelajaran</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #000;
            font-size: 10px;
        }

        .kop-laporan {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-right: 16px;
        }

        .kop-identitas {
            flex: 1;
            text-align: center;
        }

        .kop-identitas h1 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .kop-identitas h3 {
            margin: 4px 0;
            font-size: 14px;
            font-weight: normal;
        }

        .kop-identitas p {
            margin: 2px 0;
            font-size: 10px;
        }

        .judul-laporan {
            text-align: center;
            margin: 10px 0 8px;
        }

        .judul-laporan h2 {
            margin: 0;
            text-transform: uppercase;
            font-size: 16px;
        }

        .informasi-laporan {
            width: 100%;
            margin-bottom: 10px;
            font-size: 10px;
            border-collapse: collapse;
        }

        .informasi-laporan td {
            border: none;
            padding: 2px 4px;
        }

        .tabel-laporan {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-inside: auto;
        }

        .tabel-laporan th,
        .tabel-laporan td {
            border: 1px solid #999;
            padding: 5px;
            vertical-align: middle;
            overflow-wrap: break-word;
        }

        .tabel-laporan th {
            background: #eee;
            text-align: center;
        }

        .tabel-laporan tbody tr:nth-child(odd) {
            background: #f6f6f6;
        }

        .tabel-laporan thead {
            display: table-header-group;
        }

        .tabel-laporan tbody {
            display: table-row-group;
        }

        .tabel-laporan tr {
            page-break-inside: avoid;
            break-inside: avoid;
            page-break-after: auto;
        }

        .tanda-tangan {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .tanda-tangan td {
            width: 50%;
            border: none;
            text-align: center;
            vertical-align: top;
            padding: 0 30px;
        }

        .ruang-tanda-tangan {
            height: 60px;
        }

        .nama-penanda-tangan {
            font-weight: bold;
            text-decoration: underline;
        }

        @page {
            size: A4 landscape;
            margin: 8mm;
        }
    </style>
</head>

<body>

    @php
        $dataPertama = $absensiMapel->first();

        $namaKelas =
            $dataPertama->jadwalPelajaran->kelas->nama_kelas ?? '-';

        $namaMapel =
            $dataPertama->jadwalPelajaran->mataPelajaran->nama_mapel ?? '-';

        $namaGuru =
            $dataPertama->jadwalPelajaran->guru->nama_guru
            ?? $dataPertama->jadwalPelajaran->guru->user->name
            ?? auth()->user()->name
            ?? '-';

        $jamMulai =
            optional($dataPertama->jadwalPelajaran)->jam_mulai
                ? \Carbon\Carbon::parse(
                    $dataPertama->jadwalPelajaran->jam_mulai
                )->format('H:i')
                : '-';

        $jamSelesai =
            optional($dataPertama->jadwalPelajaran)->jam_selesai
                ? \Carbon\Carbon::parse(
                    $dataPertama->jadwalPelajaran->jam_selesai
                )->format('H:i')
                : '-';

        $tanggalAwal = $absensiMapel->min('tanggal');
        $tanggalAkhir = $absensiMapel->max('tanggal');
    @endphp

    <div class="kop-laporan">
        <img
            src="{{ asset('admin/assets/images/logos/logosma.png') }}"
            alt="Logo Sekolah"
            class="kop-logo">

        <div class="kop-identitas">
            <h1>SMAS Mathla'ul Anwar</h1>
            <h3>Sistem Informasi Absensi Siswa</h3>
            <p>Batujaya, Karawang</p>
        </div>
    </div>

    <div class="judul-laporan">
        <h2>Rekap Absensi Mata Pelajaran</h2>
    </div>

    <table class="informasi-laporan">
        <tr>
            <td style="width: 12%;">Mata Pelajaran</td>
            <td style="width: 38%;">: {{ $namaMapel }}</td>

            <td style="width: 12%;">Kelas</td>
            <td style="width: 38%;">: {{ $namaKelas }}</td>
        </tr>

        <tr>
            <td>Guru</td>
            <td>: {{ $namaGuru }}</td>

            <td>Jam</td>
            <td>: {{ $jamMulai }} - {{ $jamSelesai }}</td>
        </tr>

        <tr>
            <td>Periode</td>
            <td colspan="3">
                :
                @if($tanggalAwal && $tanggalAkhir)
                    {{ \Carbon\Carbon::parse($tanggalAwal)
                        ->locale('id')
                        ->translatedFormat('d F Y') }}
                    -
                    {{ \Carbon\Carbon::parse($tanggalAkhir)
                        ->locale('id')
                        ->translatedFormat('d F Y') }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    <table class="tabel-laporan">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 18%;">Nama Siswa</th>
                <th style="width: 9%;">Kelas</th>
                <th style="width: 12%;">Jam</th>
                <th style="width: 14%;">Mata Pelajaran</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 15%;">Catatan Guru</th>
                <th style="width: 8%;">Nilai Disiplin</th>
            </tr>
        </thead>

        <tbody>
            @forelse($absensiMapel as $no => $item)
                <tr>
                    <td style="text-align: center;">
                        {{ $no + 1 }}
                    </td>

                    <td>
                        {{ $item->tanggal
                            ? \Carbon\Carbon::parse($item->tanggal)
                                ->format('d-m-Y')
                            : '-' }}
                    </td>

                    <td>
                        {{ $item->siswa->nama_siswa ?? '-' }}
                    </td>

                    <td>
                        {{ $item->jadwalPelajaran->kelas->nama_kelas ?? '-' }}
                    </td>

                    <td>
                        @if(
                            optional($item->jadwalPelajaran)->jam_mulai &&
                            optional($item->jadwalPelajaran)->jam_selesai
                        )
                            {{ \Carbon\Carbon::parse(
                                $item->jadwalPelajaran->jam_mulai
                            )->format('H:i') }}
                            -
                            {{ \Carbon\Carbon::parse(
                                $item->jadwalPelajaran->jam_selesai
                            )->format('H:i') }}
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        {{ $item->jadwalPelajaran
                            ->mataPelajaran
                            ->nama_mapel ?? '-' }}
                    </td>

                    <td>
                        {{ $item->status ?? '-' }}
                    </td>

                    <td>
                        {{ $item->catatan ?? '-' }}
                    </td>

                    <td style="text-align: center;">
                        {{ $item->scan_score ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">
                        Belum ada data absensi mata pelajaran.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="tanda-tangan">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah

                <div class="ruang-tanda-tangan"></div>

                <div class="nama-penanda-tangan">
                    ........................................
                </div>

                <div>
                    NIP. ................................
                </div>
            </td>

            <td>
                Karawang,
                {{ now()
                    ->locale('id')
                    ->translatedFormat('d F Y') }}
                <br>
                Guru Mata Pelajaran

                <div class="ruang-tanda-tangan"></div>

                <div class="nama-penanda-tangan">
                    {{ $namaGuru }}
                </div>

                <div>
                    NIP. ................................
                </div>
            </td>
        </tr>
    </table>

    <script>
        window.addEventListener('load', function () {
            window.print();
        });

        window.addEventListener('afterprint', function () {
            window.history.back();
        });
    </script>

</body>
</html>