<?php

namespace App\Http\Controllers;

use App\Models\AbsensiHarian;
use App\Models\AbsensiMapel;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiMapelController extends Controller
{
    public function index()
    {
        $absensiMapel = AbsensiMapel::query()
            ->with(['siswa', 'jadwalPelajaran.mataPelajaran'])
            ->latest()
            ->paginate(20);

        return view('absensi-mapel.index', compact('absensiMapel'));
    }

    public function create()
    {
        $siswa = Siswa::query()->get();
        $mapel = MataPelajaran::query()->get();
        $tanggal = now();

        return view('absensi-mapel.form', compact('siswa', 'mapel', 'tanggal'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jadwal_pelajaran_id' => 'required|exists:jadwal_pelajaran,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Alpha,Izin,Sakit',
            'catatan' => 'nullable|string|max:500',
            'scan_score' => 'nullable|integer|min:0|max:100',
        ]);

        $data['dicatat_oleh'] = $this->getGuruId();

        AbsensiMapel::query()->create($data);

        return redirect()
            ->route('absensi-mapel.index')
            ->with('success', 'Absensi berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $absensiMapel = AbsensiMapel::query()->findOrFail($id);
        $siswa = Siswa::query()->get();
        $mapel = MataPelajaran::query()->get();
        $tanggal = $absensiMapel->tanggal;

        return view(
            'absensi-mapel.form',
            compact('absensiMapel', 'siswa', 'mapel', 'tanggal')
        );
    }

    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'jadwal_pelajaran_id' => 'required|exists:jadwal_pelajaran,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:Hadir,Alpha,Izin,Sakit',
            'catatan' => 'nullable|string|max:500',
            'scan_score' => 'nullable|integer|min:0|max:100',
        ]);

        $data['dicatat_oleh'] = $this->getGuruId();

        $absensiMapel = AbsensiMapel::query()->findOrFail($id);
        $absensiMapel->update($data);

        return redirect()
            ->route('absensi-mapel.index')
            ->with('success', 'Absensi berhasil diupdate');
    }

    public function destroy(int $id)
    {
        $absensiMapel = AbsensiMapel::query()->findOrFail($id);

        $siswaId = $absensiMapel->siswa_id;
        $tanggal = $absensiMapel->tanggal;

        $absensiMapel->delete();

        $this->sinkronkanAbsensiHarian($siswaId, $tanggal);

        return redirect()
            ->route('absensi-mapel.index')
            ->with('success', 'Absensi berhasil dihapus');
    }

    public function rekapKehadiran(Request $request)
    {
        $guruId = $this->getGuruId();

        $jadwalList = JadwalPelajaran::query()
            ->with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guruId)
            ->get();

        $jadwalId = $request->input('jadwal_id');

        $absensiMapel = AbsensiMapel::query()
            ->with([
                'siswa',
                'jadwalPelajaran.kelas',
                'jadwalPelajaran.mataPelajaran',
            ])
            ->whereNotNull('siswa_id')
            ->when($jadwalId, function ($query) use ($jadwalId) {
                $query->where('jadwal_pelajaran_id', $jadwalId);
            })
            ->when(!$jadwalId, function ($query) use ($jadwalList) {
                $query->whereIn(
                    'jadwal_pelajaran_id',
                    $jadwalList->pluck('id')
                );
            })
            ->latest()
            ->paginate(20);

        return view(
            'absensi-mapel.rekap',
            compact('absensiMapel', 'jadwalList', 'jadwalId')
        );
    }

    public function rekapPerKelas(Request $request, ?int $kelasId = null)
    {
        $kelasId = $request->input('kelas_id', $kelasId);

        $tanggalMulai = $request->input(
            'tanggal_mulai',
            now()->startOfMonth()->toDateString()
        );

        $tanggalSelesai = $request->input(
            'tanggal_selesai',
            now()->endOfMonth()->toDateString()
        );

        $absensiMapel = AbsensiMapel::query()
            ->with([
                'siswa',
                'jadwalPelajaran.mataPelajaran',
            ])
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->when($kelasId, function ($query) use ($kelasId) {
                $query->whereHas('siswa', function ($siswaQuery) use ($kelasId) {
                    $siswaQuery->where('kelas_id', $kelasId);
                });
            })
            ->orderByDesc('tanggal')
            ->paginate(20)
            ->withQueryString();

        $kelasList = Kelas::query()->get();

        return view(
            'absensi-mapel.rekap-perkelas',
            compact(
                'absensiMapel',
                'kelasList',
                'kelasId',
                'tanggalMulai',
                'tanggalSelesai'
            )
        );
    }

    public function bukaSession(int $jadwalId)
    {
        $jadwal = JadwalPelajaran::query()
            ->with('mataPelajaran')
            ->findOrFail($jadwalId);

        $kelasId = $jadwal->getAttribute('kelas_id');

        $siswa = Siswa::query()
            ->where('kelas_id', $kelasId)
            ->get();

        $tanggal = now()->toDateString();

        $absensiLama = AbsensiMapel::query()
            ->where('jadwal_pelajaran_id', $jadwalId)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return view(
            'absensi-mapel.buka-sesi',
            compact('jadwal', 'siswa', 'tanggal', 'absensiLama')
        );
    }

    public function simpanSesi(Request $request, int $jadwalId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'required|integer|exists:siswa,id',
            'status' => 'required|array',
            'status.*' => 'required|in:Hadir,Terlambat,Izin,Sakit,Alpha',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:500',
        ]);

        $guruId = $this->getGuruId();
        $tanggal = $request->input('tanggal');

        foreach ($request->input('siswa_id', []) as $siswaId) {
            $status = $request->input("status.$siswaId", 'Hadir');
            $catatan = $request->input("catatan.$siswaId");

            $templateOtomatis = [
                'Pertahankan kedisiplinan dan kehadiran yang baik.',
                'Mohon datang lebih awal agar tidak terlambat kembali.',
                'Tetap ikuti materi dan tugas yang tertinggal.',
                'Semoga lekas pulih dan segera mengejar materi yang tertinggal.',
                'Mohon tidak mengulangi ketidakhadiran tanpa keterangan.',
            ];

            $catatan = filled($catatan) ? trim($catatan) : null;

            if ($catatan !== null && in_array($catatan, $templateOtomatis, true)) {
                $catatan = null;
            }

            $nilaiDisiplin = $this->hitungNilaiDisiplin($status);
        

            AbsensiMapel::query()->updateOrCreate(
                [
                    'jadwal_pelajaran_id' => $jadwalId,
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $status,
                    'catatan' => $catatan,
                    'scan_score' => $nilaiDisiplin,
                    'dicatat_oleh' => $guruId,
                ]
            );

            $this->sinkronkanAbsensiHarian($siswaId, $tanggal);
        }

        return redirect()
            ->route('guru.jadwal-saya')
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function editSesi(int $jadwalId)
    {
        $jadwal = JadwalPelajaran::query()
            ->with('mataPelajaran')
            ->findOrFail($jadwalId);

        $kelasId = $jadwal->getAttribute('kelas_id');

        $siswa = Siswa::query()
            ->where('kelas_id', $kelasId)
            ->get();

        $tanggal = now()->toDateString();

        $absensiLama = AbsensiMapel::query()
            ->where('jadwal_pelajaran_id', $jadwalId)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return view(
            'absensi-mapel.edit-sesi',
            compact('jadwal', 'siswa', 'tanggal', 'absensiLama')
        );
    }

    public function updateSesi(Request $request, int $jadwalId)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'siswa_id' => 'required|array',
            'siswa_id.*' => 'required|integer|exists:siswa,id',
            'status' => 'required|array',
            'status.*' => 'required|in:Hadir,Terlambat,Izin,Sakit,Alpha',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:500',
        ]);

        $guruId = $this->getGuruId();
        $tanggal = $request->input('tanggal');

        foreach ($request->input('siswa_id', []) as $siswaId) {
            $status = $request->input("status.$siswaId", 'Hadir');
            $catatan = $request->input("catatan.$siswaId");

            $templateOtomatis = [
                'Pertahankan kedisiplinan dan kehadiran yang baik.',
                'Mohon datang lebih awal agar tidak terlambat kembali.',
                'Tetap ikuti materi dan tugas yang tertinggal.',
                'Semoga lekas pulih dan segera mengejar materi yang tertinggal.',
                'Mohon tidak mengulangi ketidakhadiran tanpa keterangan.',
            ];

            $catatan = filled($catatan) ? trim($catatan) : null;

            if ($catatan !== null && in_array($catatan, $templateOtomatis, true)) {
                $catatan = null;
            }

            $nilaiDisiplin = $this->hitungNilaiDisiplin($status);
        

            AbsensiMapel::query()->updateOrCreate(
                [
                    'jadwal_pelajaran_id' => $jadwalId,
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $status,
                    'catatan' => $catatan,
                    'scan_score' => $nilaiDisiplin,
                    'dicatat_oleh' => $guruId,
                ]
            );

            $this->sinkronkanAbsensiHarian($siswaId, $tanggal);
        }

        return redirect()
            ->route('guru.jadwal-saya')
            ->with('success', 'Absensi berhasil diperbarui.');
    }

    public function downloadRekap(Request $request)
    {
        $guruId = $this->getGuruId();
        $jadwalId = $request->input('jadwal_id');

        $data = AbsensiMapel::query()
            ->with([
                'siswa',
                'jadwalPelajaran.kelas',
                'jadwalPelajaran.mataPelajaran',
            ])
            ->whereHas('jadwalPelajaran', function ($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->when($jadwalId, function ($query) use ($jadwalId) {
                $query->where('jadwal_pelajaran_id', $jadwalId);
            })
            ->orderByDesc('tanggal')
            ->get();

        $filename = 'rekap_absensi_mapel_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');

            if ($file === false) {
                abort(500, 'Gagal membuat file rekap.');
            }

            fputcsv($file, [
                'Tanggal',
                'Siswa',
                'Kelas',
                'Mata Pelajaran',
                'Status',
                'Catatan',
                'Scan Score',
            ]);

            foreach ($data as $item) {
                $tanggal = $item->tanggal
                    ? Carbon::parse($item->tanggal)->format('d-m-Y')
                    : '-';

                fputcsv($file, [
                    $tanggal,
                    $item->siswa?->nama_siswa ?? '-',
                    $item->jadwalPelajaran?->kelas?->nama_kelas ?? '-',
                    $item->jadwalPelajaran?->mataPelajaran?->nama_mapel ?? '-',
                    $item->status ?? '-',
                    $item->catatan ?? '-',
                    $item->scan_score ?? '-',
                ]);
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function cetakRekap(Request $request)
    {
        $guruId = $this->getGuruId();
        $jadwalId = $request->input('jadwal_id');

        $absensiMapel = AbsensiMapel::query()
            ->with([
                'siswa',
                'jadwalPelajaran.kelas',
                'jadwalPelajaran.mataPelajaran',
            ])
            ->whereHas('jadwalPelajaran', function ($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->when($jadwalId, function ($query) use ($jadwalId) {
                $query->where('jadwal_pelajaran_id', $jadwalId);
            })
            ->orderByDesc('tanggal')
            ->get();

        return view('absensi-mapel.cetak', compact('absensiMapel'));
    }

    public function viewOrangTua()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'orang_tua') {
            abort(403, 'Akses hanya untuk orang tua/wali.');
        }

        $siswa = Siswa::query()
            ->where('user_id', $user->getAuthIdentifier())
            ->first();

        if (!$siswa) {
            $absensiMapel = collect();

            return view('absensi-mapel.orang-tua', compact('absensiMapel'))
                ->with(
                    'error',
                    'Data anak belum terhubung dengan akun orang tua/wali ini.'
                );
        }

        $absensiMapel = AbsensiMapel::query()
            ->with([
                'jadwalPelajaran.kelas',
                'jadwalPelajaran.mataPelajaran',
                'jadwalPelajaran.guru',
            ])
            ->where('siswa_id', $siswa->getKey())
            ->latest()
            ->paginate(20);

        return view('absensi-mapel.orang-tua', compact('absensiMapel', 'siswa'));
    }

    private function sinkronkanAbsensiHarian(int $siswaId, string $tanggal): void
    {
        $absensiMapelHariIni = AbsensiMapel::query()
            ->where('siswa_id', $siswaId)
            ->whereDate('tanggal', $tanggal)
            ->get();

        if ($absensiMapelHariIni->isEmpty()) {
            AbsensiHarian::where('siswa_id', $siswaId)
                ->whereDate('tanggal', $tanggal)
                ->delete();

            return;
        }

        $statusList = $absensiMapelHariIni->pluck('status');
        $terlambat = $statusList->contains('Terlambat');
        $templateOtomatis = [
            'Pertahankan kedisiplinan dan kehadiran yang baik.',
            'Mohon datang lebih awal agar tidak terlambat kembali.',
            'Tetap ikuti materi dan tugas yang tertinggal.',
            'Semoga lekas pulih dan segera mengejar materi yang tertinggal.',
            'Mohon tidak mengulangi ketidakhadiran tanpa keterangan.',
        ];

        $catatanGuru = $absensiMapelHariIni
            ->pluck('catatan')
            ->filter(function ($catatan) use ($templateOtomatis) {
                return filled($catatan)
                && !in_array(trim($catatan), $templateOtomatis, true);
            })
            ->unique()
            ->implode('; ');
            
        /*
        * Prioritas status harian:
        * 1. Alpha
        * 2. Sakit
        * 3. Izin
        * 4. Terlambat
        * 5. Hadir
        */
        if ($statusList->contains('Alpha')) {
            $statusHarian = 'Alpha';
        } elseif ($statusList->contains('Sakit')) {
            $statusHarian = 'Sakit';
        } elseif ($statusList->contains('Izin')) {
            $statusHarian = 'Izin';
        } else {
            $statusHarian = 'Hadir';
        }

        $nilaiDisiplin = match ($statusHarian) {
            'Hadir' => $statusList->contains('Terlambat') ? 75 : 100,
            'Izin', 'Sakit' => 60,
            'Alpha' => 0,
            default => 0,
        };

        $templateKeterangan = match ($statusHarian) {
            'Hadir' => $terlambat
                ? 'Mohon datang lebih awal agar tidak terlambat kembali.'
                : 'Pertahankan kedisiplinan dan kehadiran yang baik.',
            'Izin' => 'Tetap ikuti materi dan tugas yang tertinggal.',
            'Sakit' => 'Semoga lekas pulih dan segera mengejar materi yang tertinggal.',
            'Alpha' => 'Mohon tidak mengulangi ketidakhadiran tanpa keterangan.',
            default => null,
        };

        $keteranganHarian = filled($catatanGuru)
            ? $catatanGuru
            : $templateKeterangan;

        $absensiHarianLama = AbsensiHarian::query()
            ->where('siswa_id', $siswaId)
            ->whereDate('tanggal', $tanggal)
            ->first();

        $jamMasuk = null;

        if ($statusHarian === 'Hadir') {
            $jamMasuk = $absensiHarianLama?->jam_masuk ?? now()->format('H:i:s');
    }
    

    $keterangan = match ($statusHarian) {
        'Hadir' => 'Pertahankan kedisiplinan dan kehadiran yang baik.',
        'Izin' => 'Tetap semangat belajar dan lengkapi materi yang tertinggal.',
        'Sakit' => 'Semoga lekas sembuh dan segera mengikuti pembelajaran kembali.',
        'Alpha' => 'Mohon tidak mengulangi ketidakhadiran tanpa keterangan.',
        default => null,
    };

    AbsensiHarian::query()->updateOrCreate(
        [
            'siswa_id' => $siswaId,
            'tanggal' => $tanggal,
        ],
        [
            'status' => $statusHarian,
            'terlambat' => $terlambat,
            'jam_masuk' => $jamMasuk,
            'keterangan' => $keteranganHarian,
            'catatan' => $catatanGuru,
            'scan_score' => $nilaiDisiplin,
            'created_by' => Auth::id(),
        ]
    );
}

    private function getGuruId(): int
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        $guruId = $user->guru()->value('id');

        if (!$guruId) {
            abort(403, 'Akun ini belum terhubung dengan data guru.');
        }

        return (int) $guruId;
    }

    private function hitungNilaiDisiplin(string $status): int
    {
        return match ($status) {
            'Hadir' => 100,
            'Terlambat' => 75,
            'Izin', 'Sakit' => 60,
            'Alpha' => 0,
            default => 0,
        };
    }
}
