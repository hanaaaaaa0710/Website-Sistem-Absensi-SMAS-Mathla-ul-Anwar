<?php
use App\Models\AbsensiHarian;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Ambil beberapa siswa (misal 5 siswa)
$siswaList = Siswa::take(5)->get();

// Loop untuk 4 minggu (28 hari)
for ($i = 0; $i < 28; $i++) {
    $tanggal = Carbon::now()->subDays($i);

    foreach ($siswaList as $siswa) {
        // Random status
        $status = ['Hadir','Alpha','Izin','Sakit'][array_rand(['Hadir','Alpha','Izin','Sakit'])];

        AbsensiHarian::create([
            'siswa_id' => $siswa->id,
            'tanggal' => $tanggal->toDateString(),
            'jam_masuk' => $tanggal->setTime(7,30),
            'status' => $status,
            'catatan' => $status != 'Hadir' ? "Alasan $status" : null,
            'scan_score' => $status=='Hadir' ? rand(70,100) : null,
            'created_by' => 1, // id admin/guru
        ]);
    }
}