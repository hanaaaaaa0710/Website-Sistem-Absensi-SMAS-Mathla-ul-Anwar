<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\SiswaFoto;
use App\Models\AbsensiHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanWajahController extends Controller
{

    public function index()
    {
        // Admin page untuk setup scan
        if (Auth::check() && Auth::user()->role === 'admin') {
            $siswaStats = [
                'total' => Siswa::count(),
                'dengan_foto' => Siswa::has('siswaFoto')->count(),
                'tanpa_foto' => Siswa::doesntHave('siswaFoto')->count(),
            ];

            return view('scan-wajah.index', compact('siswaStats'));
        }

        // Non-admin: show scan interface
        return view('scan-wajah.interface');
    }

    public function processScan(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
        ]);

        $siswa = Siswa::find($validated['siswa_id']);
        $foto = $siswa->siswaFoto()->first();

        if (!$foto) {
            return response()->json([
                'success' => false,
                'message' => 'Foto referensi tidak ditemukan',
                'status' => 'no_reference'
            ]);
        }

        // Simulasi deteksi wajah
        $detectionResult = $this->simulateFaceDetection();

        if ($detectionResult['detected']) {
            // Simulasi matching score
            $matchScore = rand(85, 99);
            
            if ($matchScore >= 90) {
                // Berhasil dikenali - buat/update record absensi
                try {
                    AbsensiHarian::updateOrCreate(
                        [
                            'siswa_id' => $siswa->id,
                            'tanggal' => now()->toDateString(),
                        ],
                        [
                            'jam_masuk' => now()->toTimeString(),
                            'jam_scan' => now()->toTimeString(),
                            'metode_absensi' => 'Scan_Wajah',
                            'status' => 'Hadir',
                            'status_notifikasi' => 'Berhasil',
                        ]
                    );

                    return response()->json([
                        'success' => true,
                        'message' => 'Scan wajah berhasil! Absensi tercatat.',
                        'match_score' => $matchScore,
                        'siswa_nama' => $siswa->nama_siswa,
                        'siswa_nis' => $siswa->nis,
                        'jam_masuk' => now()->format('H:i:s'),
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Error saat mencatat absensi: ' . $e->getMessage(),
                    ], 500);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Wajah tidak cocok dengan data referensi (kecocokan: ' . $matchScore . '%)',
                    'match_score' => $matchScore,
                    'status' => 'mismatch'
                ]);
            }
        }

        return response()->json([
            'success' => false,
            'message' => $detectionResult['reason'],
            'status' => $detectionResult['status']
        ]);
    }

    /**
     * Simulasi deteksi wajah dengan berbagai kondisi realistis
     * 
     * @return array
     */
    private function simulateFaceDetection()
    {
        $random = rand(1, 100);

        // Kondisi error detection
        if ($random <= 5) {
            return [
                'detected' => false,
                'reason' => '❌ Wajah tidak terdeteksi. Posisikan wajah di depan kamera.',
                'status' => 'not_detected'
            ];
        }

        if ($random <= 8) {
            return [
                'detected' => false,
                'reason' => '⚠️ Wajah terlalu jauh dari kamera. Minimalkan jarak ke 30-50 cm.',
                'status' => 'too_far'
            ];
        }

        if ($random <= 11) {
            return [
                'detected' => false,
                'reason' => '⚠️ Wajah terlalu dekat dengan kamera. Maksimalkan jarak ke 30-50 cm.',
                'status' => 'too_close'
            ];
        }

        if ($random <= 14) {
            return [
                'detected' => false,
                'reason' => '⚠️ Posisi wajah tidak pas. Harap hadap ke depan, hindari posisi miring.',
                'status' => 'bad_angle'
            ];
        }

        if ($random <= 17) {
            return [
                'detected' => false,
                'reason' => '💡 Pencahayaan kurang. Tambahkan pencahayaan atau pindah ke area yang lebih terang.',
                'status' => 'bad_lighting'
            ];
        }

        // Default: berhasil terdeteksi
        return [
            'detected' => true,
            'reason' => '✓ Wajah terdeteksi dengan baik. Memproses pengenalan...',
            'status' => 'detected'
        ];
    }

    public function getSiswaForScan(Request $request)
    {
        $kelas = $request->query('kelas');
        
        $query = Siswa::where('status', 'Aktif')
            ->has('siswaFoto')
            ->orderBy('nama_siswa');

        if ($kelas) {
            $query->where('kelas_id', $kelas);
        }

        $siswa = $query->get(['id', 'nis', 'nama_siswa', 'kelas_id']);

        return response()->json([
            'success' => true,
            'data' => $siswa,
        ]);
    }

    public function getStatistikScan()
    {
        $hari_ini = now()->toDateString();

        $stats = [
            'scan_berhasil_hari_ini' => AbsensiHarian::where('tanggal', $hari_ini)
                ->where('metode_absensi', 'Scan_Wajah')
                ->where('status', 'Hadir')
                ->count(),
            
            'scan_gagal_hari_ini' => AbsensiHarian::where('tanggal', $hari_ini)
                ->where('metode_absensi', 'Scan_Wajah')
                ->where('status', '!=', 'Hadir')
                ->count(),

            'total_absensi_harian_hari_ini' => AbsensiHarian::where('tanggal', $hari_ini)
                ->count(),

            'success_rate_hari_ini' => $this->getSuccessRate($hari_ini),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    private function getSuccessRate($tanggal)
    {
        $berhasil = AbsensiHarian::where('tanggal', $tanggal)
            ->where('metode_absensi', 'Scan_Wajah')
            ->where('status', 'Hadir')
            ->count();

        $total = AbsensiHarian::where('tanggal', $tanggal)
            ->where('metode_absensi', 'Scan_Wajah')
            ->count();

        if ($total == 0) return 0;

        return round(($berhasil / $total) * 100, 2);
    }

    public function testDetection()
    {
        // Endpoint untuk testing simulasi deteksi
        $results = [];
        
        for ($i = 0; $i < 10; $i++) {
            $results[] = $this->simulateFaceDetection();
        }

        return response()->json([
            'success' => true,
            'test_results' => $results,
            'message' => 'Ini adalah test 10x simulasi deteksi wajah',
        ]);
    }
}
