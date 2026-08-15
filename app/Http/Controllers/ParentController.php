<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class ParentController extends Controller
{
    public function index($token)
    {
        // Validasi token QR/OTP
        $siswa = Siswa::where('token',$token)->firstOrFail();
        $absensi = $siswa->absensiHarian()->with('catatan')->get();

        return view('parent.dashboard', compact('siswa','absensi'));
    }
}