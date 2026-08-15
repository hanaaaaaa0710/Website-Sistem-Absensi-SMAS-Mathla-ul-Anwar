<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiHarian;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class AbsensiHarianController extends Controller
{
    public function index(Request $request)
    {
        $kelasId = $request->kelas_id;
        $tanggalHariIni = now()->toDateString();

        $absensi = AbsensiHarian::with('siswa.kelas')
            ->whereDate('tanggal', $tanggalHariIni)
            ->when($kelasId, function ($query) use ($kelasId) {
                $query->whereHas('siswa', function ($q) use ($kelasId) {
                    $q->where('kelas_id', $kelasId);
                });
            })
            ->orderBy('siswa_id')
            ->paginate(20)
            ->withQueryString();

        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('absensi-harian.index', compact('absensi', 'kelasList', 'kelasId', 'tanggalHariIni'));
    }   

    public function create()
    {
        $siswa = Siswa::orderBy('nama_siswa')->get();
        $tanggal = now();
        return view('absensi-harian.create', compact('siswa','tanggal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'=>'required|exists:siswa,id',
            'tanggal'=>'required|date',
            'status'=>'required|in:Hadir,Alpha,Izin,Sakit',
            'catatan'=>'nullable|string|max:500',
            'scan_score'=>'nullable|integer|min:0|max:100',
        ]);

        AbsensiHarian::create(array_merge($request->all(), ['created_by'=>Auth::id()]));
        return redirect()->route('absensi-harian.index')->with('success','Absensi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $absensi = AbsensiHarian::findOrFail($id);
        $siswa = Siswa::orderBy('nama_siswa')->get();
        $tanggal = $absensi->tanggal;
        return view('absensi-harian.edit', compact('absensi','siswa','tanggal'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'siswa_id'=>'required|exists:siswa,id',
            'tanggal'=>'required|date',
            'status'=>'required|in:Hadir,Alpha,Izin,Sakit',
            'catatan'=>'nullable|string|max:500',
            'scan_score'=>'nullable|integer|min:0|max:100',
        ]);

        $absensi = AbsensiHarian::findOrFail($id);
        $absensi->update(array_merge($request->all(), ['created_by'=>Auth::id()]));
        return redirect()->route('absensi-harian.index')->with('success','Absensi berhasil diupdate');
    }

    public function destroy($id)
    {
        AbsensiHarian::destroy($id);
        return redirect()->route('absensi-harian.index')->with('success','Absensi berhasil dihapus');
    }

    public function viewWaliKelas()
    {
        $guru = auth()->user()->guru;

        if (!$guru) {
            return view('dashboard.error', [
                'message' => 'Akun wali kelas belum terhubung dengan data guru.',
            ]);
        }

        if (!$guru->kelas_wali_id) {
            return view('dashboard.error', [
                'message' => 'Guru belum ditugaskan sebagai wali kelas.',
            ]);
        }

        $kelas = $guru->kelasWali;
        $kelasId = $guru->kelas_wali_id;
        $tanggal = now()->toDateString();

        $siswa = Siswa::where('kelas_id', $kelasId)
            ->orderBy('nama_siswa')
            ->get();

        $absensi = AbsensiHarian::with('siswa')
            ->whereDate('tanggal', $tanggal)
            ->whereHas('siswa', function ($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->get()
            ->keyBy('siswa_id');

        $statistik = [
            'hadir' => $absensi->where('status', 'Hadir')->count(),
            'izin' => $absensi->where('status', 'Izin')->count(),
            'sakit' => $absensi->where('status', 'Sakit')->count(),
            'alpha' => $absensi->where('status', 'Alpha')->count(),
            'terlambat' => $absensi->where('terlambat', true)->count(),
            'belum_absen' => $siswa->count() - $absensi->count(),
        ];

        return view('absensi-harian.wali-kelas-monitor', compact(
            'kelas',
            'tanggal',
            'siswa',
            'absensi',
            'statistik'
        ));
    } 

}