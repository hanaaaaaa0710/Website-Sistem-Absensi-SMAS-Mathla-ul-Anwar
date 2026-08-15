<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\User;

class GuruController extends Controller
{
    public function index()
    {
        $data_guru = Guru::with('mataPelajaran', 'kelasWali')
            ->orderBy('nama_guru')
            ->paginate(10);

        return view('guru.index', compact('data_guru'));
    }

    public function create()
    {
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('guru.create', compact('mataPelajaran','kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:40',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',

            'kelas_wali_id' => ['nullable','exists:kelas,id',
                Rule::unique('guru', 'kelas_wali_id'),],

            'tahun_ajaran_wali' => ['nullable','required_with:kelas_wali_id',
                'string','max:20','regex:/^\d{4}\/\d{4}$/',],

            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'kelas_wali_id.unique'  =>
                'Kelas tersebut sudah memiliki wali kelas.',
            
            'tahun_ajaran_wali.required_with' =>
                'Tahun ajaran wajib diisi jika guru ditetapkan sebagai wali kelas.',

            'tahun_ajaran_wali.regex' =>
                'Format tahun ajaran harus seperti 2026/2027.',
        ]);

        DB::transaction(function () use ($request) {
            $time = now()->format('YmdHis');

            $user = User::create([
                'name' => $request->nama_guru,
                'email' => 'guru' . $time . rand(100, 999) . '@gmail.com',
                'password' => Hash::make('password123'),
                'role' => $request->filled('kelas_wali_id')
                    ? 'wali_kelas'
                    : 'guru',
            ]);

            Guru::create([
                'user_id' => $user->id,
                'nip' => 'G' . str_pad($user->id, 3, '0', STR_PAD_LEFT),
                'nama_guru' => $request->nama_guru,
                'jenis_kelamin' => 'L',
                'ttl' => '-',
                'mata_pelajaran_id' => $request->mata_pelajaran_id,
                'kelas_wali_id'=>$request->kelas_wali_id,
                'tahun_ajaran_wali'=> $request->filled('kelas_wali_id')
                    ? $request->tahun_ajaran_wali
                    : null,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan. Password default: password123');
    }

    public function edit(Guru $guru)
    {
        $mataPelajaran = MataPelajaran::orderBy('nama_mapel')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('guru.edit', compact('guru', 'mataPelajaran', 'kelas'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:40',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',

            'kelas_wali_id' => [
                'nullable',
                'exists:kelas,id',
                Rule::unique('guru', 'kelas_wali_id')->ignore($guru->id),
            ],

            'tahun_ajaran_wali' => [
                'nullable',
                'required_with:kelas_wali_id',
                'string',
                'max:20',
                'regex:/^\d{4}\/\d{4}$/',
            ],

            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'kelas_wali_id.unique' =>
                'Kelas tersebut sudah memiliki wali kelas.',

            'tahun_ajaran_wali.required_with' =>
                'Tahun ajaran wajib diisi jika guru ditetapkan sebagai wali kelas.',

            'tahun_ajaran_wali.regex' =>
                'Format tahun ajaran harus seperti 2026/2027.',
        ]);

        $guru->update([
            'nama_guru' => $request->nama_guru,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'kelas_wali_id'=> $request->kelas_wali_id,
            'tahun_ajaran_wali'=> $request->filled('kelas_wali_id')
                ? $request->tahun_ajaran_wali
                : null,
            'status' => $request->status,
        ]);

        if ($guru->user) {
            $guru->user->update([
                'name' => $request->nama_guru,
                'role' => $request->filled('kelas_wali_id')
                    ? 'wali_kelas'
                    : 'guru',
            ]);
        }

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diupdate.');
    }

    public function destroy(Guru $guru)
    {
        if ($guru->user) {
            $guru->user->delete();
        } else {
            $guru->delete();
        }

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }

    public function formGantiPassword()
    {
        return view('user.ganti-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama' => 'required',
            'password_baru' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->with('error', 'Password lama salah.');
        }

        $user->password = Hash::make($request->password_baru);
        $user->save();

        return back()->with('success', 'Password berhasil diganti.');
    }

    public function show(Guru $guru)
    {
        return view('guru.show', compact('guru'));
    }
}