<?php
use App\Models\AbsensiMapel;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;

// Ambil semua jadwal pelajaran
$jadwalList = JadwalPelajaran::all();

// Loop 20 hari terakhir
for ($i = 0; $i < 20; $i++) {
    $tanggal = Carbon::now()->subDays($i);

    foreach ($jadwalList as $jadwal) {
        // Random status
        $status = ['Hadir','Alpha','Izin','Sakit'][array_rand(['Hadir','Alpha','Izin','Sakit'])];

        AbsensiMapel::create([
            'siswa_id' => $jadwal->siswa_id,
            'jadwal_pelajaran_id' => $jadwal->id,
            'tanggal' => $tanggal->toDateString(),
            'jam_masuk' => $tanggal->setTime(7,30),
            'status' => $status,
            'catatan' => $status != 'Hadir' ? "Alasan $status" : null,
            'scan_score' => $status=='Hadir' ? rand(70,100) : null,
            'dicatat_oleh' => 1, // id guru
        ]);
    }
}