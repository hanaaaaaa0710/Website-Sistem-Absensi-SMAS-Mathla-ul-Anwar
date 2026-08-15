<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPelajaranController extends Controller
{

    public function index(Request $request)
    {
        $query = JadwalPelajaran::with('kelas', 'guru', 'mataPelajaran');

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwal = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(20);

        $kelas = Kelas::aktif()->get();
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('jadwal-pelajaran.index', compact('jadwal', 'kelas', 'hari_list'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $guru = Guru::aktif()->get();
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('jadwal-pelajaran.create', compact('kelas', 'guru', 'mataPelajaran', 'hari_list'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'jam_mulai'   => $request->jam_mulai ? substr($request->jam_mulai, 0, 5) : null,
            'jam_selesai' => $request->jam_selesai ? substr($request->jam_selesai, 0, 5) : null,
        ]);

        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:guru,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'semester' => 'required|in:1,2',
            'tahun_ajaran' => 'required|max:9',
        ]);

        $cek = JadwalPelajaran::where('kelas_id', $validated['kelas_id'])
            ->where('hari', $validated['hari'])
            ->where('jam_mulai', $validated['jam_mulai'])
            ->exists();

        if ($cek) {
            return back()
                ->withInput()
                ->with('error', 'Jadwal sudah ada untuk kelas, hari, dan jam tersebut.');
        }
        $kelas = Kelas::find($validated['kelas_id']);
        $validated['ruang_kelas'] = $kelas->nama_kelas ?? null;
        JadwalPelajaran::create($validated);

        return redirect()->route('jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan');
    }

    public function edit(JadwalPelajaran $jadwalPelajaran)
    {
        
        $kelas = Kelas::all();
        $guru = Guru::aktif()->get();

        $mataPelajaran = MataPelajaran::aktif('nama_mapel')->get();

        $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('jadwal-pelajaran.edit', compact(
            'jadwalPelajaran', 'kelas', 'guru', 'mataPelajaran', 'hari_list'
        ));
    }

    public function update(Request $request, JadwalPelajaran $jadwalPelajaran)
{
    $request->merge([
        'jam_mulai'   => $request->jam_mulai ? substr($request->jam_mulai, 0, 5) : null,
        'jam_selesai' => $request->jam_selesai ? substr($request->jam_selesai, 0, 5) : null,
    ]);

    $validated = $request->validate([
        'kelas_id' => 'required|exists:kelas,id',
        'guru_id' => 'required|exists:guru,id',
        'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
        'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required|after:jam_mulai',
        'semester' => 'required|in:1,2',
        'tahun_ajaran' => 'required|max:9',
    ]);

    $cek = JadwalPelajaran::where('kelas_id', $validated['kelas_id'])
        ->where('hari', $validated['hari'])
        ->where('jam_mulai', $validated['jam_mulai'])
        ->where('id', '!=', $jadwalPelajaran->id)
        ->exists();

    if ($cek) {
        return back()
            ->withInput()
            ->with('error', 'Jadwal sudah ada untuk kelas, hari, dan jam tersebut.');
    }

    $kelas = Kelas::find($validated['kelas_id']);
    $validated['ruang_kelas'] = $kelas->nama_kelas ?? null;

    $jadwalPelajaran->update($validated);

    return redirect()->route('jadwal-pelajaran.index')
        ->with('success', 'Jadwal pelajaran berhasil diperbarui');
}

    public function destroy(JadwalPelajaran $jadwalPelajaran)
    {
        $jadwalPelajaran->delete();
        return redirect()->route('jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil dihapus');
    }

    // Untuk guru, lihat jadwal mengajar mereka
    public function jadwalSaya()
    {
        if (Auth::user()->role !== 'guru') {
            abort(403);
        }

        $guru = Auth::user()->guru;
        $jadwal = JadwalPelajaran::where('guru_id', $guru->id)
            ->aktif()
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('jadwal-pelajaran.jadwal-saya', compact('jadwal'));
}
    public function jadwalOrangTua()
    {
        if (Auth::user()->role !== 'orang_tua') {
            abort(403, 'Akses hanya untuk orang tua/wali.');
        }
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return back()->with('error', 'Data anak belum terhubung dengan akun orang tua/wali ini.');
        }

        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'guru', 'kelas'])
            ->where('kelas_id', $siswa->kelas_id)
            ->orderByRaw("
                FIELD(
                    hari,
                    'Senin',
                    'Selasa',
                    'Rabu',
                    'Kamis',
                    'Jumat',
                    'Sabtu'
                )
            ")
            ->orderBy('jam_mulai')
            ->get();

        return view('jadwal-pelajaran.orang-tua', compact('jadwal', 'siswa'));
    }
}