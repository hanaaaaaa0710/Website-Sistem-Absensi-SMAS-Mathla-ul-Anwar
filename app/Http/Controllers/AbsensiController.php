<?php

namespace App\Http\Controllers;

use App\Models\AbsensiHarian;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    private function hitungNilaiDisiplin($status)
    {
        return match ($status) {
            'Hadir' => 100,
            'Terlambat' => 75,
            'Izin' => 60,
            'Sakit' => 60,
            'Alpha' => 0,
            default => 0,
        };
    }

    public function index(Request $request)
    {
        $query = AbsensiHarian::with('siswa.kelas');

        if ($request->filled('search')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('nama_siswa', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data_absensi = $query->latest()->paginate(10)->withQueryString();

        $scanSiswa = Siswa::with('kelas')->where('status', 'Aktif')->first();

        $statusTerakhir = AbsensiHarian::with('siswa')
            ->whereDate('tanggal', today())
            ->latest()
            ->take(5)
            ->get();

        return view('absensi.index', [
            'data_absensi' => $data_absensi,
            'scanSiswa' => $scanSiswa,
            'statusTerakhir' => $statusTerakhir,
            'totalTerdeteksi' => AbsensiHarian::whereDate('tanggal', today())->count(),
            'waktuScan' => now()->format('H:i'),
            'nilaiDisiplin' => 100,
        ]);
    }

    public function create()
    {
        $data_siswa = Siswa::with('kelas')
            ->where('status', 'Aktif')
            ->orderBy('nama_siswa')
            ->get();

        return view('absensi.create', compact('data_siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha,Terlambat',
            'keterangan' => 'nullable|string|max:500',
            'status_notifikasi' => 'nullable|in:Berhasil,Gagal,Menunggu',
        ]);

        $nilaiDisiplin = $this->hitungNilaiDisiplin($request->status);

        $statusHarian = $request->status === 'Terlambat'
            ? 'Hadir'
            : $request->status;

        AbsensiHarian::create([
            'siswa_id' => $request->siswa_id,
            'tanggal' => $request->tanggal,
            'jam_masuk' => in_array($request->status, ['Hadir', 'Terlambat'])
                ? ($request->jam_masuk ?? now()->format('H:i:s'))
                : null,
            'status' => $statusHarian,
            'keterangan' => $request->status === 'Terlambat'
                ? 'Terlambat'
                : $request->keterangan,
            'status_notifikasi' => $request->status_notifikasi ?? 'Menunggu',
            'scan_score' => $nilaiDisiplin,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('absensi.index', ['tab' => 'riwayat'])
            ->with('sukses', 'Data absensi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $absensi = AbsensiHarian::findOrFail($id);

        $data_siswa = Siswa::with('kelas')
            ->where('status', 'Aktif')
            ->orderBy('nama_siswa')
            ->get();

        return view('absensi.edit', compact('absensi', 'data_siswa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable',
            'status' => 'required|in:Hadir,Izin,Sakit,Alpha,Terlambat',
            'keterangan' => 'nullable|string|max:500',
            'status_notifikasi' => 'required|in:Berhasil,Gagal,Menunggu',
        ]);

        $absensi = AbsensiHarian::findOrFail($id);

        $nilaiDisiplin = $this->hitungNilaiDisiplin($request->status);

        $statusHarian = $request->status === 'Terlambat'
            ? 'Hadir'
            : $request->status;

        $absensi->update([
            'siswa_id' => $request->siswa_id,
            'tanggal' => $request->tanggal,
            'jam_masuk' => in_array($request->status, ['Hadir', 'Terlambat'])
                ? ($request->jam_masuk ?? now()->format('H:i:s'))
                : null,
            'status' => $statusHarian,
            'keterangan' => $request->status === 'Terlambat'
                ? 'Terlambat'
                : $request->keterangan,
            'status_notifikasi' => $request->status_notifikasi,
            'scan_score' => $nilaiDisiplin,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('absensi.index', ['tab' => 'riwayat'])
            ->with('sukses', 'Data absensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        AbsensiHarian::findOrFail($id)->delete();

        return redirect()->route('absensi.index', ['tab' => 'riwayat'])
            ->with('sukses', 'Data absensi berhasil dihapus.');
    }

    public function scanHadir(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
        ]);

        AbsensiHarian::updateOrCreate(
            [
                'siswa_id' => $request->siswa_id,
                'tanggal' => today()->toDateString(),
            ],
            [
                'jam_masuk' => now()->format('H:i:s'),
                'status' => 'Hadir',
                'keterangan' => 'Hadir dengan nilai disiplin baik',
                'status_notifikasi' => 'Menunggu',
                'scan_score' => 100,
                'created_by' => Auth::id(),
            ]
        );

        return redirect()->route('absensi.index')
            ->with('sukses', 'Absensi berhasil disimpan.');
    }
}