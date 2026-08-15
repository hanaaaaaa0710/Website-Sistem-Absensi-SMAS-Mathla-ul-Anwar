<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use App\Models\AbsensiHarian;
use App\Models\AbsensiMapel;
use App\Models\CatatanAbsensi;
use App\Exports\AbsensiMapelExport;
use App\Exports\AbsensiHarianExport;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $this->dashboardAdmin();
        } elseif ($user->role === 'guru') {
            return $this->dashboardGuru();
        } elseif ($user->role === 'wali_kelas') {
            return $this->dashboardWaliKelas();
        } elseif ($user->role === 'orang_tua') {
            return $this->dashboardOrangTua();
        }

        abort(403);
    }

    protected function dashboardAdmin()
    {
        $stats = [
            'total_guru' => Guru::count(),
            'total_siswa' => Siswa::count(),
            'total_kelas' => Kelas::count(),
            'total_users' => User::count(),
        ];

        $guruAktif = Guru::where('status', 'Aktif')->count();
        $siswaAktif = Siswa::where('status', 'Aktif')->count();

        // Semua data guru
        $data_guru = Guru::with('user')->orderBy('nama_guru')->paginate(10, ['*'], 'guru_page');

        // Absensi Mapel Terbaru (Ringkasan Dashboard)
        $absensiQuery = AbsensiMapel::with([
            'siswa',
            'jadwalPelajaran.kelas',
            'jadwalPelajaran.mataPelajaran'
        ]);

        if (request()->filled('kelas_id')) {
            $absensiQuery->whereHas('jadwalPelajaran', function ($q) {
                $q->where('kelas_id', request('kelas_id'));
            });
        }

        if (request()->filled('mata_pelajaran_id')) {
            $absensiQuery->whereHas('jadwalPelajaran', function ($q) {
                $q->where('mata_pelajaran_id', request('mata_pelajaran_id'));
            });
        }

        $absensi = $absensiQuery
            ->latest('tanggal')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'stats',
            'guruAktif',
            'siswaAktif',
            'data_guru',
            'absensi'
        ));
    }

    public function exportAbsensiMapel(Request $request)
    {
        $kelasId = $request->get('kelas_id');
        $mapelId = $request->get('mata_pelajaran_id');

        return Excel::download(
            new AbsensiMapelExport($request->only([
                'kelas_id',
                'mata_pelajaran_id',
                'tanggal_dari',
                'tanggal_sampai',
                'status',
                'siswa_id',
            ])),
            'rekap-absensi-mapel.xlsx'
        );
    }

   public function exportAbsensiHarian(Request $request)
    {
        return Excel::download(
            new AbsensiHarianExport($request->only([
                'kelas_id',
                'tanggal_dari',
                'tanggal_sampai',
                'status',
                'siswa_id',
            ])),
            'rekap-absensi-harian.xlsx'
        );
    } 

    protected function dashboardGuru()
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            return view('dashboard.error', [
                'message' => 'Akun guru belum terhubung dengan data guru.'
            ]);
        }

        $absensiMapel = AbsensiMapel::with(
            'siswa',
            'jadwalPelajaran.mataPelajaran',
            'jadwalPelajaran.kelas'
        )
        ->where('dicatat_oleh', $guru->id)
        ->latest()
        ->take(10)
        ->get();

        $hari = now()->locale('id')->translatedFormat('l');

        $jadwalHariIni = \App\Models\JadwalPelajaran::with([
            'kelas',
            'mataPelajaran'
        ])
        ->where('guru_id', $guru->id)
        ->where('hari', $hari)
        ->orderBy('jam_mulai')
        ->get();

        $statistik = [
            'hadir' => AbsensiMapel::where('dicatat_oleh', $guru->id)->where('status', 'Hadir')->count(),
            'alpha' => AbsensiMapel::where('dicatat_oleh', $guru->id)->where('status', 'Alpha')->count(),
            'izin' => AbsensiMapel::where('dicatat_oleh', $guru->id)->where('status', 'Izin')->count(),
            'sakit' => AbsensiMapel::where('dicatat_oleh', $guru->id)->where('status', 'Sakit')->count(),
        ];

        return view('dashboard.guru', compact('absensiMapel', 'jadwalHariIni', 'statistik'));
    }

    protected function dashboardWaliKelas()
    {
        $guru = Auth::user()->guru;

        if (!$guru) {
            return view('dashboard.error', [
                'message' => 'Akun wali kelas belum terhubung dengan data guru.'
            ]);
        }

        if (!$guru->kelas_wali_id) {
            return view('dashboard.error', [
                'message' => 'Guru belum ditugaskan sebagai wali kelas.',
            ]);
        }

        $kelasId = $guru->kelas_wali_id;

        $bulan = now()->month;
        $tahun = now()->year;

        /*
        |--------------------------------------------------------------------------
        | Rekap Kehadiran Terbaru
        |--------------------------------------------------------------------------
        | Menampilkan maksimal 10 data absensi harian terbaru pada kelas wali.
        */
        $absensiHarian = AbsensiHarian::with('siswa')
            ->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->latest('tanggal')
            ->latest('id')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Absensi Mata Pelajaran Terbaru
        |--------------------------------------------------------------------------
        | Menampilkan maksimal 10 data absensi mapel terbaru pada kelas wali.
        */
        $absensiMapel = AbsensiMapel::with([
            'siswa',
            'jadwalPelajaran.mataPelajaran',
            'jadwalPelajaran.kelas',
        ])
            ->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            })
            ->latest('tanggal')
            ->latest('id')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistik Kehadiran Bulan Berjalan
        |--------------------------------------------------------------------------
        | Menggunakan absensi harian agar satu siswa hanya dihitung satu kali
        | untuk setiap tanggal.
        */
        $queryStatistik = AbsensiHarian::query()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });

        $statistik = [
            'bulan' => now()->translatedFormat('F Y'),

            'hadir' => (clone $queryStatistik)
                ->where('status', 'Hadir')
                ->count(),

            'izin' => (clone $queryStatistik)
                ->where('status', 'Izin')
                ->count(),

            'sakit' => (clone $queryStatistik)
                ->where('status', 'Sakit')
                ->count(),

            'alpha' => (clone $queryStatistik)
                ->where('status', 'Alpha')
                ->count(),

            'terlambat' => (clone $queryStatistik)
                ->where('terlambat', true)
                ->count(),
        ];

        return view('dashboard.wali_kelas', compact(
            'absensiHarian',
            'absensiMapel',
            'statistik',
            'guru'
        ));
    }
    
    protected function dashboardOrangTua()
    {
        $siswa = \App\Models\Siswa::with('kelas')
        ->where('user_id', auth()->id())
        ->first();

        if (!$siswa) {
            return view('dashboard.error', [
                'message' => 'Data anak belum terhubung dengan akun orang tua/wali ini.'
            ]);
        }

        $absensi = AbsensiHarian::query()
            ->where('siswa_id', $siswa->id)
            ->latest()
            ->get();

        $stats = [
            'hadir' => $absensi->where('status', 'Hadir')->count(),
            'alpha' => $absensi->where('status', 'Alpha')->count(),
            'izin' => $absensi->where('status', 'Izin')->count(),
            'sakit' => $absensi->where('status', 'Sakit')->count(),
        ];

        $catatan = AbsensiHarian::query()
            ->where('siswa_id', $siswa->id)
            ->whereNotNull('keterangan')
            ->where('keterangan', '!=', '')
            ->latest('tanggal')
            ->latest('id')
            ->take(10)
            ->get();
        

        $tahun = now()->year;
        $bulan = collect(range(1, 12));

        $monthly = [
            'tanggal' => $bulan
                ->map(fn ($b) => \Carbon\Carbon::create($tahun, $b, 1)
                    ->locale('id')
                    ->translatedFormat('M'))
                ->values(),

            'hadir' => $bulan->map(fn ($b) =>
                AbsensiHarian::where('siswa_id', $siswa->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $b)
                    ->where('status', 'Hadir')
                    ->count()
            )->values(),

            'alpha' => $bulan->map(fn ($b) =>
                AbsensiHarian::where('siswa_id', $siswa->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $b)
                    ->where('status', 'Alpha')
                    ->count()
            )->values(),

            'izin' => $bulan->map(fn ($b) =>
                AbsensiHarian::where('siswa_id', $siswa->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $b)
                    ->where('status', 'Izin')
                    ->count()
            )->values(),

            'sakit' => $bulan->map(fn ($b) =>
                AbsensiHarian::where('siswa_id', $siswa->id)
                    ->whereYear('tanggal', $tahun)
                    ->whereMonth('tanggal', $b)
                    ->where('status', 'Sakit')
                    ->count()
            )->values(),
        ];

        return view('dashboard.orang_tua', compact('siswa', 'stats', 'catatan', 'monthly'));
    }
}