<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('kelas');

        if ($request->filled('search')) {
            $query->where('nama_siswa', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $data_siswa = $query->orderBy('nama_siswa')
            ->paginate(10)
            ->withQueryString();

        $total_siswa = Siswa::count();

        $list_kelas = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.index', compact('data_siswa', 'total_siswa', 'list_kelas'));
    }

    public function create()
    {
        $list_kelas = Kelas::orderBy('nama_kelas')->get();
        return view('siswa.create', compact('list_kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:40',
            'jenis_kelamin' => 'required|in:L,P',
            'ttl' => 'required|string|max:35',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'hubungan_ortu' => 'required|string|max:15',
            'nama_ortu' => 'required|string|max:40',
            'no_hp_ortu' => 'required|string|max:20',
            'email_ortu' => 'required|email|max:50|unique:users,email',
            'password_ortu' => 'required|string|min:6|confirmed',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['nama_ortu'],
                'email' => $validated['email_ortu'],
                'password' => Hash::make($validated['password_ortu']),
                'role' => 'orang_tua',
                'is_active' => $validated['status'] === 'Aktif',
            ]);

            Siswa::create([
                'user_id' => $user->id,
                'nis' => 'S' . time(),
                'nama_siswa' => $validated['nama_siswa'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'ttl' => $validated['ttl'],
                'kelas_id' => $validated['kelas_id'],
                'nama_ortu' => $validated['nama_ortu'],
                'no_hp_ortu' => $validated['no_hp_ortu'],
                'hubungan_ortu' => $validated['hubungan_ortu'],
                'status' => $validated['status'],
            ]);
        });

        return redirect()
            ->route('siswa.index')
            ->with('sukses', 'Data siswa dan akun orang tua/wali berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);

        $validated = $request->validate([
            'nama_siswa' => 'required|string|max:40',
            'jenis_kelamin' => 'required|in:L,P',
            'ttl' => 'required|string|max:35',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'hubungan_ortu' => 'required|string|max:15',
            'nama_ortu' => 'required|string|max:40',
            'no_hp_ortu' => 'required|string|max:20',

            'email_ortu' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users', 'email')->ignore($siswa->user_id),
            ],

            'password_ortu' => 'nullable|string|min:6|confirmed',
        ]);

        DB::transaction(function () use ($validated, $siswa) {
            $user = $siswa->user;

            if (!$user) {
                $user = User::create([
                    'name' => $validated['nama_ortu'],
                    'email' => $validated['email_ortu'],
                    'password' => Hash::make(
                        $validated['password_ortu'] ?: 'password123'
                    ),
                    'role' => 'orang_tua',
                    'is_active' => $validated['status'] === 'Aktif',
                ]);

                $siswa->user_id = $user->id;
            } else {
                $user->name = $validated['nama_ortu'];
                $user->email = $validated['email_ortu'];
                $user->role = 'orang_tua';
                $user->is_active = $validated['status'] === 'Aktif';

                if (!empty($validated['password_ortu'])) {
                    $user->password = Hash::make($validated['password_ortu']);
                }

                $user->save();
            }

            $siswa->nama_siswa = $validated['nama_siswa'];
            $siswa->jenis_kelamin = $validated['jenis_kelamin'];
            $siswa->ttl = $validated['ttl'];
            $siswa->kelas_id = $validated['kelas_id'];
            $siswa->nama_ortu = $validated['nama_ortu'];
            $siswa->no_hp_ortu = $validated['no_hp_ortu'];
            $siswa->hubungan_ortu = $validated['hubungan_ortu'];
            $siswa->status = $validated['status'];
            $siswa->save();
        });

        return redirect()
            ->route('siswa.index')
            ->with('sukses', 'Data siswa dan akun orang tua/wali berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);

        DB::transaction(function () use ($siswa) {
            $user = $siswa->user;

            $siswa->delete();

            if ($user) {
                $user->delete();
            }
        });

        return redirect()
            ->route('siswa.index')
            ->with('sukses', 'Data siswa dan akun orang tua/wali berhasil dihapus.');
    }

    public function delete($id)
    {
        return $this->destroy($id);
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new SiswaExport,
            'data-siswa.xlsx'
        );
    }

    public function profilSaya()
    {
        $user = auth()->user();

        if ($user->role === 'orang_tua') {
            $user->load('siswa.kelas');
        }

        if ($user->role === 'guru') {
            $user->load('guru.mataPelajaran');
        }

        if ($user->role === 'wali_kelas') {
            $user->load('guru.kelasWali');
        }

        return view('profile.show', compact('user'));
    }

    public function profil()
    {
        return $this->profilSiswa();
    }

    public function formGantiPassword()
    {
        $siswa = auth()->user()->siswa;
        return view('siswa.ganti-password', compact('siswa'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }

    public function profilAnak()
    {
        $user = auth()->user();

        if ($user->role !== 'orang_tua') {
            abort(403, 'Akses hanya untuk orang tua/wali.');
        }

        $siswa = Siswa::with('kelas')
            ->where('user_id', $user->id)
            ->first();

        if (!$siswa) {
            return view('dashboard.error', [
                'message' => 'Data anak belum terhubung dengan akun orang tua/wali ini.',
            ]);
        }

        return view('orang-tua.profil-anak', compact('user', 'siswa'));
    }
}