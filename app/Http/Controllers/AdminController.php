<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\User;
use App\Models\AbsensiMapel;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SiswaExport;
use App\Exports\AbsensiMapelExport;
use App\Models\Notifikasi;

class AdminController extends Controller
{
    // Dashboard
    public function dashboard(Request $request)
    {
        $today = now()->toDateString();

        $stats = [
            'total_guru' => Guru::count(),
            'total_siswa' => Siswa::count(),
            'total_kelas' => Kelas::count(),
            'total_users' => User::count(),
        ];

        $guruAktif = Guru::where('status','Aktif')->count();
        $siswaAktif = Siswa::where('status','Aktif')->count();

        $listKelas = Kelas::orderBy('nama_kelas')->get();
        $listMapel = MataPelajaran::orderBy('nama_mapel')->get();

        $absensiQuery = AbsensiMapel::with('siswa','jadwalPelajaran.kelas','jadwalPelajaran.mataPelajaran');

        if($request->filled('kelas_id')){
            $absensiQuery->whereHas('siswa.kelas', fn($q)=> $q->where('id',$request->kelas_id));
        }
        if($request->filled('mata_pelajaran_id')){
            $absensiQuery->whereHas('jadwalPelajaran.mataPelajaran', fn($q)=> $q->where('id',$request->mata_pelajaran_id));
        }

        $absensi = $absensiQuery->latest()->paginate(10);

        return view('dashboard.admin', compact(
            'stats','guruAktif','siswaAktif','listKelas','listMapel','absensi'
        ));
    }

    // ================= Siswa =================
    public function siswaIndex(Request $request)
    {
        $query = Siswa::query();
        if($request->filled('search')) $query->where('nama_siswa','like','%'.$request->search.'%');
        if($request->filled('kelas')) $query->where('kelas',$request->kelas);
        if($request->filled('status')) $query->where('status',$request->status);

        $data_siswa = $query->paginate(10)->withQueryString();
        $list_kelas = Siswa::select('kelas')->distinct()->pluck('kelas');

        return view('siswa.index', compact('data_siswa','list_kelas'));
    }

    public function siswaCreate()
    {
        return view('siswa.create');
    }

    public function siswaStore(Request $request)
    {
        $request->validate([
            'nama_siswa'=>'required',
            'ttl'=>'required',
            'jenis_kelamin'=>'required',
            'kelas'=>'required',
            'status'=>'required'
        ]);

        Siswa::create($request->all());
        return redirect()->route('admin.siswa.index')->with('success','Siswa berhasil ditambahkan');
    }

    public function siswaEdit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('siswa.edit', compact('siswa'));
    }

    public function siswaUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_siswa'=>'required',
            'ttl'=>'required',
            'jenis_kelamin'=>'required',
            'kelas'=>'required',
            'status'=>'required'
        ]);

        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->all());
        return redirect()->route('admin.siswa.index')->with('success','Siswa berhasil diupdate');
    }

    public function siswaDestroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success','Siswa berhasil dihapus');
    }

    public function siswaExport()
    {
        return Excel::download(new SiswaExport, 'siswa.xlsx');
    }

    // ================= Guru =================
    public function guruIndex()
    {
        $guru = Guru::paginate(10);
        return view('guru.index', compact('guru'));
    }

    public function guruCreate(){ return view('guru.create'); }
    public function guruStore(Request $request){ /* validasi + create */ }
    public function guruEdit($id){ /* edit guru */ }
    public function guruUpdate(Request $request,$id){ /* update guru */ }
    public function guruDestroy($id){ /* delete guru */ }

    public function notifikasiIndex(){ $notifikasi = Notifikasi::latest()->paginate(10); return view('notifikasi.index',compact('notifikasi')); }
    public function notifikasiSend(Request $request){ /* kirim notifikasi manual */ }

}