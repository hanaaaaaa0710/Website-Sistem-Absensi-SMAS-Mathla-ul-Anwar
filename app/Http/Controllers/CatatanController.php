<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CatatanAbsensi;
use Illuminate\Support\Facades\Auth;

class CatatanController extends Controller
{
    public function index()
    {
        $catatan = CatatanAbsensi::with('siswa','creator')->latest()->paginate(20);
        return view('catatan.index', compact('catatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'absensi_id'=>'required|exists:absensi_harian,id',
            'siswa_id'=>'required|exists:siswa,id',
            'catatan'=>'required|string|max:500'
        ]);

        CatatanAbsensi::create([
            'absensi_id'=>$request->absensi_id,
            'siswa_id'=>$request->siswa_id,
            'catatan'=>$request->catatan,
            'created_by'=>Auth::id()
        ]);

        return redirect()->back()->with('success','Catatan absensi berhasil ditambahkan');
    }
}