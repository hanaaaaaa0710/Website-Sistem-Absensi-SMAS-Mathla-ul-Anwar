<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\AbsensiHarian;
use App\Models\AbsensiMapel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $tahunAjaran = '2025/2026';

        $admin = User::create([
            'name' => 'Admin SIMAWA',
            'email' => 'admin@simawa.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $kelasData = [
            ['kode_kelas' => 'XIPA', 'nama_kelas' => 'X IPA', 'tingkat' => 10, 'jurusan' => 'IPA', 'tahun' => 2010],
            ['kode_kelas' => 'XIPS', 'nama_kelas' => 'X IPS', 'tingkat' => 10, 'jurusan' => 'IPS', 'tahun' => 2010],
            ['kode_kelas' => 'XIIPA', 'nama_kelas' => 'XI IPA', 'tingkat' => 11, 'jurusan' => 'IPA', 'tahun' => 2009],
            ['kode_kelas' => 'XIIPS', 'nama_kelas' => 'XI IPS', 'tingkat' => 11, 'jurusan' => 'IPS', 'tahun' => 2009],
            ['kode_kelas' => 'XIIIPA', 'nama_kelas' => 'XII IPA', 'tingkat' => 12, 'jurusan' => 'IPA', 'tahun' => 2008],
            ['kode_kelas' => 'XIIIPS', 'nama_kelas' => 'XII IPS', 'tingkat' => 12, 'jurusan' => 'IPS', 'tahun' => 2008],
        ];

        $kelasList = [];

        foreach ($kelasData as $item) {
            $kelasList[] = [
                'kelas' => Kelas::create([
                    'kode_kelas' => $item['kode_kelas'],
                    'nama_kelas' => $item['nama_kelas'],
                    'tingkat' => $item['tingkat'],
                    'jurusan' => $item['jurusan'],
                    'kapasitas' => 40,
                    'tahun_ajaran' => $tahunAjaran,
                ]),
                'tahun' => $item['tahun'],
            ];
        }

        $mtk = MataPelajaran::create([
            'kode_mapel' => 'MTK',
            'nama_mapel' => 'Matematika',
            'deskripsi' => 'Mata pelajaran Matematika',
            'sks' => 2,
            'is_aktif' => true,
        ]);

        $ekonomi = MataPelajaran::create([
            'kode_mapel' => 'EKO',
            'nama_mapel' => 'Ekonomi',
            'deskripsi' => 'Mata pelajaran Ekonomi',
            'sks' => 2,
            'is_aktif' => true,
        ]);

        $mapelTambahan = [
            ['kode' => 'BIN', 'nama' => 'Bahasa Indonesia'],
            ['kode' => 'BIG', 'nama' => 'Bahasa Inggris'],
            ['kode' => 'FIS', 'nama' => 'Fisika'],
            ['kode' => 'KIM', 'nama' => 'Kimia'],
            ['kode' => 'BIO', 'nama' => 'Biologi'],
            ['kode' => 'SEJ', 'nama' => 'Sejarah'],
            ['kode' => 'GEO', 'nama' => 'Geografi'],
            ['kode' => 'SOS', 'nama' => 'Sosiologi'],
            ['kode' => 'PKN', 'nama' => 'PKN'],
            ['kode' => 'PAI', 'nama' => 'Pendidikan Agama Islam'],
        ];

        foreach ($mapelTambahan as $item) {
            MataPelajaran::create([
                'kode_mapel' => $item['kode'],
                'nama_mapel' => $item['nama'],
                'deskripsi' => 'Mata pelajaran ' . $item['nama'],
                'sks' => 2,
                'is_aktif' => true,
            ]);
        }

        $userGuruMtk = User::create([
            'name' => 'Guru Matematika',
            'email' => 'guru.mtk@simawa.com',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        $guruMtk = Guru::create([
            'user_id' => $userGuruMtk->id,
            'nip' => 'G001',
            'nama_guru' => 'Guru Matematika',
            'jenis_kelamin' => 'L',
            'ttl' => 'Bandung, 12-03-1985',
            'mata_pelajaran_id' => $mtk->id,
            'status' => 'Aktif',
        ]);

        $userGuruEkonomi = User::create([
            'name' => 'Guru Ekonomi',
            'email' => 'guru.ekonomi@simawa.com',
            'password' => Hash::make('password123'),
            'role' => 'guru',
            'is_active' => true,
        ]);

        $guruEkonomi = Guru::create([
            'user_id' => $userGuruEkonomi->id,
            'nip' => 'G002',
            'nama_guru' => 'Guru Ekonomi',
            'jenis_kelamin' => 'P',
            'ttl' => 'Jakarta, 08-11-1987',
            'mata_pelajaran_id' => $ekonomi->id,
            'status' => 'Aktif',
        ]);


        $waliNames = [
            ['name' => 'Wali Kelas X IPA', 'email' => 'wali.xipa@simawa.com', 'jk' => 'P'],
            ['name' => 'Wali Kelas X IPS', 'email' => 'wali.xips@simawa.com', 'jk' => 'L'],
            ['name' => 'Wali Kelas XI IPA', 'email' => 'wali.xiipa@simawa.com', 'jk' => 'P'],
            ['name' => 'Wali Kelas XI IPS', 'email' => 'wali.xiips@simawa.com', 'jk' => 'L'],
            ['name' => 'Wali Kelas XII IPA', 'email' => 'wali.xiiipa@simawa.com', 'jk' => 'P'],
            ['name' => 'Wali Kelas XII IPS', 'email' => 'wali.xiiips@simawa.com', 'jk' => 'L'],
        ];

        foreach ($kelasList as $index => $kelasItem) {
            $userWali = User::create([
                'name' => $waliNames[$index]['name'],
                'email' => $waliNames[$index]['email'],
                'password' => Hash::make('password123'),
                'role' => 'wali_kelas',
                'is_active' => true,
            ]);

            $guruWali = Guru::create([
                'user_id' => $userWali->id,
                'nip' => 'WK' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'nama_guru' => $waliNames[$index]['name'],
                'jenis_kelamin' => $waliNames[$index]['jk'],
                'ttl' => 'Serang, 10-05-1986',
                'mata_pelajaran_id' => $index % 2 == 0 ? $mtk->id : $ekonomi->id,
                'status' => 'Aktif',

                'kelas_wali_id' => $kelasItem['kelas']->id,
                'tahun_ajaran_wali' => $tahunAjaran,
            ]);
        }

        $jadwalPerKelas = [
            [
                'kelas' => $kelasList[0]['kelas'], // X IPA
                'mtk_hari' => 'Senin',
                'mtk_mulai' => '07:30',
                'mtk_selesai' => '09:00',
                'eko_hari' => 'Selasa',
                'eko_mulai' => '09:30',
                'eko_selesai' => '11:00',
            ],
            [
                'kelas' => $kelasList[1]['kelas'], // X IPS
                'mtk_hari' => 'Senin',
                'mtk_mulai' => '09:30',
                'mtk_selesai' => '11:00',
                'eko_hari' => 'Selasa',
                'eko_mulai' => '11:15',
                'eko_selesai' => '12:45',
            ],
            [
                'kelas' => $kelasList[2]['kelas'], // XI IPA
                'mtk_hari' => 'Rabu',
                'mtk_mulai' => '07:30',
                'mtk_selesai' => '09:00',
                'eko_hari' => 'Kamis',
                'eko_mulai' => '09:30',
                'eko_selesai' => '11:00',
            ],
            [
                'kelas' => $kelasList[3]['kelas'], // XI IPS
                'mtk_hari' => 'Rabu',
                'mtk_mulai' => '09:30',
                'mtk_selesai' => '11:00',
                'eko_hari' => 'Kamis',
                'eko_mulai' => '11:15',
                'eko_selesai' => '12:45',
            ],
            [
                'kelas' => $kelasList[4]['kelas'], // XII IPA
                'mtk_hari' => 'Jumat',
                'mtk_mulai' => '07:30',
                'mtk_selesai' => '09:00',
                'eko_hari' => 'Sabtu',
                'eko_mulai' => '09:30',
                'eko_selesai' => '11:00',
            ],
            [
                'kelas' => $kelasList[5]['kelas'], // XII IPS
                'mtk_hari' => 'Jumat',
                'mtk_mulai' => '09:30',
                'mtk_selesai' => '11:00',
                'eko_hari' => 'Sabtu',
                'eko_mulai' => '11:15',
                'eko_selesai' => '12:45',
            ],
        ];

        foreach ($jadwalPerKelas as $item) {
            JadwalPelajaran::create([
                'kelas_id' => $item['kelas']->id,
                'guru_id' => $guruMtk->id,
                'mata_pelajaran_id' => $mtk->id,
                'hari' => $item['mtk_hari'],
                'jam_mulai' => $item['mtk_mulai'],
                'jam_selesai' => $item['mtk_selesai'],
                'ruang_kelas' => $item['kelas']->nama_kelas,
                'semester' => 1,
                'tahun_ajaran' => $tahunAjaran,
                'is_aktif' => true,
            ]);

            JadwalPelajaran::create([
                'kelas_id' => $item['kelas']->id,
                'guru_id' => $guruEkonomi->id,
                'mata_pelajaran_id' => $ekonomi->id,
                'hari' => $item['eko_hari'],
                'jam_mulai' => $item['eko_mulai'],
                'jam_selesai' => $item['eko_selesai'],
                'ruang_kelas' => $item['kelas']->nama_kelas,
                'semester' => 1,
                'tahun_ajaran' => $tahunAjaran,
                'is_aktif' => true,
            ]);

        }

        $namaLaki = [
            'Ahmad Rizky Pratama','Muhammad Fajar Maulana','Rafi Saputra','Dimas Ramadhan','Andika Firmansyah',
            'Farhan Hidayat','Rizal Setiawan','Ilham Fauzi','Arif Kurniawan','Fikri Akbar',
            'Bagas Saputra','Rangga Maulana','Reza Febriansyah','Naufal Ramadhan','Raka Firmansyah',
            'Farel Hidayat','Rian Kurniawan','Aldi Prasetyo','Fadli Ramadhan','Rendy Saputra',
            'Kevin Maulana','Daffa Pratama','Gilang Saputra','Arya Nugraha','Bima Setiawan',
            'Yoga Ramadhan','Rizki Fadillah','Iqbal Maulana','Alif Hidayat','Hafiz Pratama',
            'Rifki Ramadhan','Aditya Saputra','Doni Kurniawan','Taufik Hidayat','Wildan Maulana',
            'Syahrul Ramadhan','Fahri Pratama','Raihan Akbar','Dzaki Maulana','Reno Saputra',
            'Davin Pratama','Nanda Hidayat','Agung Setiawan','Galang Ramadhan','Rizwan Maulana',
            'Rizky Ananda','Fathan Maulana','Rafiq Hidayat','Bayu Prasetyo','Evan Saputra',
            'Zidan Ramadhan','Azka Maulana','Habib Pratama','Dani Firmansyah','Arkan Saputra',
            'Rizal Fahmi','Fauzan Akbar','Rama Setiawan','Yusuf Maulana','Ilham Pradana',
            'Adit Ramadhan','Fariz Hidayat','Gibran Maulana','Rifqi Saputra','Kenzi Pratama',
            'Maulana Yusuf','Rendra Saputra','Fahmi Ramadhan','Naufal Hidayat','Raka Pradipta',
            'Rizki Saputra','Fadlan Maulana','Rafi Ananda','Dimas Hidayat','Bagus Pratama',
            'Satria Ramadhan','Arjuna Maulana','Rizal Prakoso','Rangga Hidayat','Aldi Saputra',
            'Fikri Ramadhan','Azzam Maulana','Revan Saputra','Fahrel Hidayat','Rendy Maulana',
            'Aqil Pratama','Bintang Saputra','Raffi Ramadhan','Farel Prakoso','Alvin Maulana',
            'Riski Firmansyah','Rafli Saputra','Daffa Hidayat','Fajar Kurniawan','Naufan Maulana',
            'Reza Pratama','Rian Saputra','Fadli Hidayat','Arya Ramadhan','Ilham Maulana'
        ];

        $namaPerempuan = [
            'Siti Nurhaliza','Aulia Putri Rahma','Nabila Zahra','Intan Permatasari','Dewi Lestari',
            'Aisyah Ramadhani','Putri Maharani','Nadia Oktaviani','Rani Anggraini','Fitri Handayani',
            'Safira Amalia','Anisa Rahma','Dinda Ayu Lestari','Larasati Putri','Nayla Khairunnisa',
            'Zahra Azzahra','Riska Amelia','Maya Safitri','Citra Lestari','Salma Nabila',
            'Nur Aini','Salsabila Putri','Rara Anggraini','Adinda Rahma','Tiara Maharani',
            'Nazwa Oktaviani','Syifa Amalia','Alya Ramadhani','Laila Putri','Indah Permata',
            'Melati Safitri','Yuni Anggraini','Dwi Lestari','Nisa Khairunnisa','Mutiara Zahra',
            'Sabrina Putri','Aurelia Rahma','Keyla Maharani','Niken Amalia','Hana Safitri',
            'Farah Oktaviani','Dewi Rahmawati','Cinta Lestari','Rahma Aulia','Alia Putri',
            'Az Zahra','Nabila Putri','Dina Rahma','Mila Anggraini','Vina Safitri',
            'Suci Ramadhani','Risma Amalia','Fitria Lestari','Nadya Permata','Putri Azzahra',
            'Ayu Lestari','Anjani Rahma','Dhea Amalia','Nasywa Putri','Shafira Maharani',
            'Kirana Safitri','Zaskia Rahma','Najwa Amalia','Feby Anggraini','Sinta Lestari',
            'Rania Putri','Annisa Maharani','Dara Oktaviani','Lina Rahmawati','Maya Permata',
            'Aqila Zahra','Fathia Putri','Kayla Ramadhani','Ratu Anggraini','Wulan Safitri',
            'Nanda Amalia','Diana Lestari','Yasmin Rahma','Rizka Putri','Bella Maharani',
            'Amira Khairunnisa','Safa Oktaviani','Tasya Amelia','Naura Putri','Nindy Lestari',
            'Raisa Rahma','Dewi Anggraini','Salsa Amalia','Nabila Safitri','Aulia Maharani',
            'Zahwa Putri','Hanifah Ramadhani','Rena Lestari','Vira Oktaviani','Della Amalia',
            'Naila Safitri','Maharani Putri','Nadira Rahma','Laras Anggraini','Siska Amalia'
        ];

        $kota = [
            'Jakarta','Bandung','Serang','Pandeglang','Lebak','Tangerang','Cilegon','Bogor','Depok','Bekasi',
            'Yogyakarta','Semarang','Surabaya','Malang','Solo','Lampung','Palembang','Medan','Padang','Makassar',
            'Banjarmasin','Pontianak','Denpasar','Mataram','Pekanbaru','Jambi','Sukabumi','Cirebon','Tasikmalaya','Garut'
        ];

        $siswaByKelas = [];
        $nomorSiswa = 1;

        $jumlahPerKelas = [34,34,33,33,33,33];

        foreach ($kelasList as $kelasIndex => $dataKelas) {

            for ($j = 1; $j <= $jumlahPerKelas[$kelasIndex]; $j++) {

                $jenisKelamin = $j % 2 == 0 ? 'P' : 'L';

                if ($jenisKelamin == 'L') {
                    $nama = $namaLaki[array_rand($namaLaki)];
                } else {
                    $nama = $namaPerempuan[array_rand($namaPerempuan)];
                }

                $tanggal = str_pad(rand(1,28), 2, '0', STR_PAD_LEFT);
                $bulan = str_pad(rand(1,12), 2, '0', STR_PAD_LEFT);
                $kotaLahir = $kota[array_rand($kota)];

                $userSiswa = User::create([
                    'name' => $nama,
                    'email' => 'siswa'.$nomorSiswa.'@simawa.com',
                    'password' => Hash::make('password123'),
                    'role' => 'siswa',
                    'is_active' => true,
                ]);

                $siswa = Siswa::create([
                    'user_id' => $userSiswa->id,
                    'kelas_id' => $dataKelas['kelas']->id,
                    'nis' => 'S'.str_pad($nomorSiswa, 4, '0', STR_PAD_LEFT),
                    'nama_siswa' => $nama,
                    'jenis_kelamin' => $jenisKelamin,
                    'ttl' => $kotaLahir.', '.$tanggal.'-'.$bulan.'-'.$dataKelas['tahun'],
                    'status' => 'Aktif',
                    'tahun_ajaran' => $tahunAjaran,
                ]);

                $siswaByKelas[$dataKelas['kelas']->id][] = $siswa;

                $nomorSiswa++;
            }
        }

        foreach (JadwalPelajaran::with('kelas')->get() as $jadwal) {
            $siswaDalamKelas = $siswaByKelas[$jadwal->kelas_id] ?? [];

            foreach (array_slice($siswaDalamKelas, 0, 10) as $index => $siswa) {
                $status = ['Hadir', 'Hadir', 'Hadir', 'Izin', 'Sakit', 'Alpha'][$index % 6];

                AbsensiMapel::create([
                    'jadwal_pelajaran_id' => $jadwal->id,
                    'siswa_id' => $siswa->id,
                    'tanggal' => now()->subDays($index)->toDateString(),
                    'jam_masuk' => $status === 'Hadir' ? '07:30:00' : null,
                    'status' => $status,
                    'keterangan' => $status === 'Hadir' ? null : 'Keterangan ' . $status,
                    'catatan' => $status === 'Hadir' ? null : 'Catatan ' . $status,
                    'scan_score' => $status === 'Hadir' ? 95 : null,
                    'dicatat_oleh' => $jadwal->guru_id,
                ]);
            }
        }

        foreach (Siswa::take(30)->get() as $index => $siswa) {
            $status = ['Hadir', 'Hadir', 'Izin', 'Sakit', 'Alpha'][$index % 5];

            AbsensiHarian::create([
                'siswa_id' => $siswa->id,
                'tanggal' => now()->subDays($index % 10)->toDateString(),
                'jam_masuk' => $status === 'Hadir' ? '07:15:00' : null,
                'status' => $status,
                'keterangan' => $status === 'Hadir' ? null : 'Keterangan ' . $status,
                'status_notifikasi' => ['Berhasil', 'Menunggu', 'Gagal'][$index % 3],
                'catatan' => $status === 'Hadir' ? null : 'Catatan ' . $status,
                'scan_score' => $status === 'Hadir' ? 96 : null,
                'created_by' => $admin->id,
            ]);
        }

        $hubunganList = ['Ayah', 'Ibu', 'Wali'];

        $namaOrangTuaList = [
            'Hendra Setiawan', 'Sulastri Rahmawati', 'Dedi Kurniawan', 'Sri Wahyuni',
            'Agus Santoso', 'Nurhayati', 'Rudi Hartono', 'Yuliana Safitri',
            'Bambang Prasetyo', 'Kartika Dewi', 'Slamet Riyadi', 'Dewi Kartini',
            'Joko Susanto', 'Ratna Sari', 'Eko Prasetyo', 'Murniati',
            'Teguh Wibowo', 'Lilis Suryani', 'Asep Firmansyah', 'Neneng Hasanah',
        ];

        $siswaSemua = Siswa::all();

        foreach ($siswaSemua as $index => $siswa) {
            $siswa->nama_ortu = $namaOrangTuaList[array_rand($namaOrangTuaList)];
            $siswa->no_hp_ortu = '08' . rand(1111111111, 9999999999);
            $siswa->hubungan_ortu = $hubunganList[array_rand($hubunganList)];

            $siswa->save();
        }
    }
    
}