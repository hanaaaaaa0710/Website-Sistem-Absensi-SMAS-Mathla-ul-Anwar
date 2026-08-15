<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'=>'required|string|max:30',
            'email'=>'required|email|max:30|unique:users,email,'.$user->id,
            'password'=>'nullable|min:6|confirmed',
            'foto'=>'nullable|image|max:1024'
        ]);

        if($request->hasFile('foto')){
            $path = $request->file('foto')->store('public/foto');
            $user->foto = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password) $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success','Profil berhasil diperbarui');
    }

    public function formGantiPassword()
    {
        return view('auth.ganti-password');
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

    public function profilSaya()
    {
        $user = auth()->user();

        if ($user->role === 'guru') {
            $user->load('guru.mataPelajaran');
        }

        if ($user->role === 'wali_kelas') {
            $user->load('guru.kelasWali');
        }

        return view('profile.show', compact('user'));
    }
}