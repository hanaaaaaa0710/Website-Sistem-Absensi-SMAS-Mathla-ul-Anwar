<?php

namespace App\Http\Controllers;

use App\Models\AbsensiMapel;
use App\Models\AbsensiHarian;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\MataPelajaran;       

class LaporanController extends Controller
{

    /**
     * Laporan Absensi Harian
     */
    public function absensiHarian(Request $request)
    {
        $statQuery = AbsensiHarian::query();

        // Filter by tanggal
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $statQuery->whereBetween('tanggal', [
                $request->tanggal_dari,
                $request->tanggal_sampai
            ]);
        } else {
            // Default bulan ini
            $statQuery->whereMonth('tanggal', now()->month)
                      ->whereYear('tanggal', now()->year);
        }

        // Filter by status
        if ($request->filled('status')) {
            $statQuery->where('status', $request->status);
        }

        // Filter by siswa
        if ($request->filled('siswa_id')) {
            $statQuery->where('siswa_id', $request->siswa_id);
        }

        // Filter by kelas
        if ($request->filled('kelas_id')) {
            $statQuery->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        $queryAbsensi = clone $statQuery;

        if ($request->boolean('cetak')) {
            $absensi = $queryAbsensi
                ->with(['siswa.kelas'])
                ->orderBy('tanggal', 'desc')
                ->orderBy('siswa_id')
                ->get();
        } else {
            $absensi = $queryAbsensi
                ->with(['siswa.kelas'])
                ->orderBy('tanggal', 'desc')
                ->orderBy('siswa_id')
                ->paginate(50)
                ->withQueryString();
        }

        // Statistik (mengikuti filter)
        $statQuery = clone $statQuery;

        $statistik = [
            'hadir' => (clone $statQuery)
                ->where('status','Hadir')
                ->count(),

            'izin' => (clone $statQuery)
                ->where('status','Izin')
                ->count(),

            'sakit' => (clone $statQuery)
                ->where('status','Sakit')
                ->count(),

            'alpha' => (clone $statQuery)
                ->where('status','Alpha')
                ->count(),

            'terlambat' => (clone $statQuery)
                ->where('terlambat', true)
                ->count(),
        ];

        $kelas = Kelas::orderBy('nama_kelas', 'asc')->get();

        $siswa = Siswa::query()
        ->orderBy('nama_siswa', 'asc')
        ->get();

        $statusOptions = ['Hadir', 'Izin', 'Sakit', 'Alpha', 'Terlambat'];

        $dataView = compact('absensi', 'statistik', 'kelas', 'siswa', 'statusOptions');

        if ($request->boolean('cetak')) {
            return view('laporan.absensi-harian-cetak', $dataView);
        }

        return view('laporan.absensi-harian', $dataView);
    }

    /**
     * Laporan Absensi Per Mata Pelajaran
     */
    public function absensiMapel(Request $request)
    {
        $query = AbsensiMapel::with([
            'siswa.kelas',
            'jadwalPelajaran.kelas',
            'jadwalPelajaran.mataPelajaran',
            'dicatatOleh',
        ]);

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal', [
                $request->tanggal_dari,
                $request->tanggal_sampai
            ]);
        } else {
            $query->whereMonth('tanggal', now()->month)
                  ->whereYear('tanggal', now()->year);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kelas_id')) {
            $query->whereHas('jadwalPelajaran', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }

        if ($request->filled('mata_pelajaran_id')) {
            $query->whereHas('jadwalPelajaran', function ($q) use ($request) {
                $q->where('mata_pelajaran_id', $request->mata_pelajaran_id);
            });
        }

        if ($request->filled('siswa_id')) {
            $query->where('siswa_id', $request->siswa_id);
        }

        $statQuery = clone $query;

        if ($request->boolean('cetak')) {

            $absensiMapel = $query
                ->orderBy('tanggal', 'desc')
                ->orderBy('siswa_id')
                ->get();

        } else {

            $absensiMapel = $query
                ->orderBy('tanggal', 'desc')
                ->orderBy('siswa_id')
                ->paginate(50)
                ->withQueryString();
        }

        $statistik = [
            'hadir' => (clone $statQuery)->where('status', 'Hadir')->count(),
            'izin' => (clone $statQuery)->where('status', 'Izin')->count(),
            'sakit' => (clone $statQuery)->where('status', 'Sakit')->count(),
            'alpha' => (clone $statQuery)->where('status', 'Alpha')->count(),
            'terlambat' => (clone $statQuery)->where('status', 'Terlambat')->count(),
        ];

        $kelas = Kelas::orderBy('nama_kelas')->get();
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        $siswa = Siswa::orderBy('nama_siswa')->get();

        $statusOptions = [
            'Hadir',
            'Izin',
            'Sakit',
            'Alpha',
            'Terlambat'
        ];

        $dataView = compact(
            'absensiMapel',
            'statistik',
            'statusOptions',
            'kelas',
            'mataPelajaran',
            'siswa'
        );

        if ($request->boolean('cetak')) {
            return view('laporan.absensi-mapel-cetak', $dataView);
        }

        return view('laporan.absensi-mapel', $dataView);
    }

    /**
     * Laporan Siswa Alpha (sering tidak hadir)
     */
    public function siswaAlpha(Request $request)
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $minAlpha = $request->input('min_alpha', 3); // Minimal 3 kali alpha

        // Ambil siswa yang alpha minimal N kali dalam bulan
        $siswaAlpha = DB::table('siswa')
            ->select(
                'siswa.id',
                'siswa.nama_siswa',
                'siswa.kelas_id',
                'kelas.nama_kelas',
                DB::raw('COUNT(*) as total_alpha')
            )
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->join('absensi_harian', 'siswa.id', '=', 'absensi_harian.siswa_id')
            ->whereMonth('absensi_harian.tanggal', $bulan)
            ->whereYear('absensi_harian.tanggal', $tahun)
            ->where('absensi_harian.status', 'Alpha')
            ->where('siswa.status', 'Aktif')
            ->groupBy('siswa.id', 'siswa.nama_siswa', 'siswa.kelas_id', 'kelas.nama_kelas')
            ->having('total_alpha', '>=', $minAlpha)
            ->orderBy('total_alpha', 'desc')
            ->paginate(20);

        // Detail alpha per siswa
        $detailAlpha = [];
        foreach ($siswaAlpha as $siswa) {
            $detailAlpha[$siswa->id] = AbsensiHarian::where('siswa_id', $siswa->id)
                ->where('status', 'Alpha')
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->orderBy('tanggal')
                ->get(['tanggal', 'keterangan']);
        }

        return view('laporan.siswa-alpha', compact(
            'siswaAlpha', 'detailAlpha', 'bulan', 'tahun'
        ));
    }

    /**
     * Laporan untuk Guru - Siswa Alpha di Mata Pelajaran Mereka
     */
    public function laporanAlphaGuru(Request $request)
    {
        if (Auth::user()->role !== 'guru') {
            abort(403);
        }

        $guru = Auth::user()->guru;
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $minAlpha = $request->input('min_alpha', 1);

        // Ambil siswa alpha dari mapel guru
        $siswaAlpha = DB::table('siswa')
            ->select(
                'siswa.id',
                'siswa.nama_siswa',
                DB::raw('COUNT(*) as total_alpha')
            )
            ->join('absensi_mapel', 'siswa.id', '=', 'absensi_mapel.siswa_id')
            ->join('jadwal_pelajaran', 'absensi_mapel.jadwal_pelajaran_id', '=', 'jadwal_pelajaran.id')
            ->where('jadwal_pelajaran.guru_id', $guru->id)
            ->whereMonth('absensi_mapel.tanggal', $bulan)
            ->whereYear('absensi_mapel.tanggal', $tahun)
            ->where('absensi_mapel.status', 'Alpha')
            ->groupBy('siswa.id', 'siswa.nama_siswa')
            ->having('total_alpha', '>=', $minAlpha)
            ->orderBy('total_alpha', 'desc')
            ->get();

        return view('laporan.guru-alpha', compact(
            'siswaAlpha', 'bulan', 'tahun'
        ));
    }

    /**
     * Laporan untuk Wali Kelas - Siswa Alpha di Kelas
     */
    public function laporanAlphaWaliKelas(Request $request)
    {
        if (Auth::user()->role !== 'wali_kelas') {
            abort(403);
        }

        $guru = Auth::user()->guru;
        $kelas = $guru->kelasWali;

        if (!$kelas) {
            return view('errors.not-found', [
                'message' => 'Anda belum ditugaskan sebagai wali kelas'
            ]);
        }

        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $minAlpha = $request->input('min_alpha', 1);

        // Siswa di kelas ini yang alpha di bulan ini
       $siswaAlpha = DB::table('siswa')
            ->select(
                'siswa.id',
                'siswa.nama_siswa',
                DB::raw('COUNT(*) as total_alpha')
            )
            ->where('siswa.kelas_id', $kelas->id)
            ->where('siswa.status', 'Aktif')
            ->join('absensi_harian', 'siswa.id', '=', 'absensi_harian.siswa_id')
            ->whereMonth('absensi_harian.tanggal', $bulan)
            ->whereYear('absensi_harian.tanggal', $tahun)
            ->where('absensi_harian.status', 'Alpha')
            ->groupBy('siswa.id', 'siswa.nama_siswa')
            ->having('total_alpha', '>=', $minAlpha)
            ->orderBy('total_alpha', 'desc')
            ->get();

        return view('laporan.wali-kelas-alpha', compact(
            'siswaAlpha', 'kelas', 'bulan', 'tahun'
        ));
    }

    /**
     * Rekap Kehadiran Bulanan - untuk Wali Kelas
     */
    public function rekapKehadiranWaliKelas(Request $request)
    {
        if (Auth::user()->role !== 'wali_kelas') {
            abort(403);
        }

        $guru = Auth::user()->guru;
        $kelas = $guru->kelasWali;

        if (!$kelas) {
            return view('errors.not-found', [
                'message' => 'Anda belum ditugaskan sebagai wali kelas'
            ]);
        }

        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);

        // Ambil semua siswa aktif di kelas
        $siswa = $kelas->siswa()->where('status', 'Aktif')->get();

        // Hitung statistik per siswa
        $rekap = [];
        foreach ($siswa as $s) {
            $absensiHarian = $s->absensiHarian()
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->get();

            $hadir = $absensiHarian->where('status', 'Hadir')->count();
            $izin = $absensiHarian->where('status', 'Izin')->count();
            $sakit = $absensiHarian->where('status', 'Sakit')->count();
            $alpha = $absensiHarian->where('status', 'Alpha')->count();
            $terlambat = $absensiHarian->where('terlambat', true)->count();

            $total = $hadir + $izin + $sakit + $alpha;


            $persentase = $total > 0 
                ? round(($hadir / $total) * 100, 2)
            : 0;

            $rekap[] = [
                'siswa' => $s,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'terlambat' => $terlambat,
                'total' => $total,
                'persentase' => $persentase,
            ];
        }

        return view('laporan.wali-kelas-rekap', compact(
            'kelas', 'rekap', 'bulan', 'tahun'
        ));
    }


    /**
     * Export Laporan ke Excel (TODO: Integrate Maatwebsite Excel)
     */
    public function exportExcel(Request $request)
    {
        // Implementasi export menggunakan Maatwebsite/Excel
        // TODO: Implementasi lengkap dengan export template
        return back()->with('info', 'Fitur export sedang dalam pengembangan');
    }

    /**
     * Export Laporan ke PDF (TODO: Integrate mPDF atau Dompdf)
     */
    public function exportPdf(Request $request)
    {
        // Implementasi export PDF
        // TODO: Implementasi lengkap dengan PDF generation
        return back()->with('info', 'Fitur PDF export sedang dalam pengembangan');
    }

    public function rekapAbsensiPerKelas($kelasId, $mapelId)
    {
        $absensi = AbsensiMapel::with('siswa', 'jadwalPelajaran.mataPelajaran')
            ->whereHas('siswa', fn($q) => $q->where('kelas_id', $kelasId))
            ->whereHas('jadwalPelajaran', fn($q) => $q->where('mata_pelajaran_id', $mapelId))
            ->orderBy('tanggal', 'desc')
            ->get();

        $kelas = Kelas::findOrFail($kelasId);
        $mataPelajaran = MataPelajaran::findOrFail($mapelId);

        return view('laporan.rekap-perkelas', compact('absensi', 'kelas', 'mataPelajaran'));
    }
}
