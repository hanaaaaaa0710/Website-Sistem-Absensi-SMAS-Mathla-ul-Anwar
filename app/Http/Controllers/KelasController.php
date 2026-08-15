<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas')->paginate(10);
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:40',
            'tingkat' => 'required|integer|in:10,11,12',
            'tahun_ajaran' => 'required|string|max:50',
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
            'kode_kelas' => strtoupper(substr($request->nama_kelas, 0, 3)) . rand(10,99),
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return redirect()->route('kelas.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    public function edit(Kelas $kelas)
    {
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => 'required|string|max:40',
            'tingkat' => 'required|integer|in:10,11,12',
            'tahun_ajaran' => 'required|string|max:50',
        ]);

        $kelas->update([
            'nama_kelas' => $validated['nama_kelas'],
            'tingkat' => $validated['tingkat'],
            'tahun_ajaran' => $validated['tahun_ajaran'],
        ]);

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        if ($kelas->siswa()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus kelas yang masih memiliki siswa');
        }

        $kelas->delete();

        return redirect()->route('kelas.index')
            ->with('success', 'Data kelas berhasil dihapus');
    }
}