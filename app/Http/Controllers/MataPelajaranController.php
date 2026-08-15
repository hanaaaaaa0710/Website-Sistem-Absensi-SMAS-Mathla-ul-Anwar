<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{

    public function index(Request $request)
    {
        $query = MataPelajaran::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_mapel', 'like', "%$search%")
                  ->orWhere('kode_mapel', 'like', "%$search%");
        }

        if ($request->filled('is_aktif')) {
            $query->where('is_aktif', $request->is_aktif);
        }

        $mataPelajaran = $query->orderBy('nama_mapel')->paginate(15);
        
        return view('mata-pelajaran.index', compact('mataPelajaran'));
    }

    public function create()
    {
        return view('mata-pelajaran.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|unique:mata_pelajaran|max:15',
            'nama_mapel' => 'required|max:40',
            'deskripsi' => 'nullable|string',
            'sks' => 'required|integer|min:1|max:4',
            'is_aktif' => 'required|boolean',
        ]);

        MataPelajaran::create($validated);
        
        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan');
    }

    public function show(MataPelajaran $mataPelajaran)
    {
        return view('mata-pelajaran.show', compact('mataPelajaran'));
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        return view('mata-pelajaran.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $validated = $request->validate([
            'kode_mapel' => 'required|unique:mata_pelajaran,kode_mapel,' . $mataPelajaran->id . '|max:15',
            'nama_mapel' => 'required|max:40',
            'deskripsi' => 'nullable|string',
            'sks' => 'required|integer|min:1|max:4',
            'is_aktif' => 'required|boolean',
        ]);

        $mataPelajaran->update($validated);
        
        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        if ($mataPelajaran->jadwalPelajaran()->count() > 0) {
            return back()->with('error', 'Tidak bisa menghapus mata pelajaran yang masih digunakan di jadwal');
        }

        $mataPelajaran->delete();
        
        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus');
    }
}
