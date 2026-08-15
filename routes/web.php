<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AbsensiHarianController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\AbsensiMapelController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CatatanController;
use App\Http\Middleware\CheckRole;


/*
---------------------Guest---------------------


*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.proses');
});

/*
    ---------------------Authenticated---------------------

*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/ganti-password', [UserController::class, 'formGantiPassword'])->name('user.ganti-password');
    Route::post('/ganti-password', [UserController::class, 'updatePassword'])->name('user.update-password');

    Route::get('/profile.show', [UserController::class, 'profilSaya'])->name('profile.show');

    Route::middleware([CheckRole::class . ':admin'])->prefix('admin')->group(function () {
        Route::post('/notifikasi/store-template',[NotifikasiController::class, 'storeTemplate'])->name('admin.notifikasi.storeTemplate');

        Route::get('/notifikasi/{id}/whatsapp',[NotifikasiController::class, 'openWhatsapp'])->name('admin.notifikasi.whatsapp.open');

        Route::put('/notifikasi/{id}/whatsapp/confirm',[NotifikasiController::class, 'confirmWhatsapp'])->name('admin.notifikasi.whatsapp.confirm');
    });

    Route::middleware([CheckRole::class . ':wali_kelas'])->prefix('wali-kelas')->group(function () {
        Route::post('/notifikasi/store-template',[NotifikasiController::class, 'storeTemplate'])->name('wali-kelas.notifikasi.storeTemplate');

        Route::get('/notifikasi/{id}/whatsapp',[NotifikasiController::class, 'openWhatsapp'])->name('wali-kelas.notifikasi.whatsapp.open');

        Route::put('/notifikasi/{id}/whatsapp/confirm',[NotifikasiController::class, 'confirmWhatsapp'])->name('wali-kelas.notifikasi.whatsapp.confirm');
    });
    /*
    
    ---------------------Admin---------------------
    */
    Route::middleware([CheckRole::class . ':admin'])->prefix('admin')->group(function () {

        Route::get('/dashboard',[DashboardController::class, 'index'])->name('admin.dashboard');

        // Rekap Absensi Mapel
        Route::get('/export-absensi-mapel', [DashboardController::class, 'exportAbsensiMapel'])
            ->name('admin.export-absensi-mapel');

        // Rekap Absensi Harian
        Route::get('/export-absensi-harian', [DashboardController::class, 'exportAbsensiHarian'])
            ->name('admin.export-absensi-harian');

        Route::get('siswa/export', [SiswaController::class, 'exportExcel'])->name('siswa.export');
        Route::resource('siswa', SiswaController::class)->except(['show']);

        Route::resource('guru', GuruController::class);
        Route::resource('kelas', KelasController::class)->parameters(['kelas' => 'kelas']);
        Route::resource('mata-pelajaran', MataPelajaranController::class);
        Route::resource('jadwal-pelajaran', JadwalPelajaranController::class);



        Route::resource('absensi-harian', AbsensiHarianController::class)->only (['index']);

        Route::prefix('laporan')->group(function () {
            Route::get('/absensi-harian', [LaporanController::class, 'absensiHarian'])->name('laporan.absensi-harian');
            Route::get('/absensi-mapel', [LaporanController::class, 'absensiMapel'])->name('laporan.absensi-mapel');
            Route::get('/siswa-alpha', [LaporanController::class, 'siswaAlpha'])->name('laporan.siswa-alpha');
            Route::get('/semester', [LaporanController::class, 'semester'])->name('laporan.semester');
            Route::get('/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');

            Route::get('/rekap-absensi', [LaporanController::class, 'rekapAbsensiAdmin'])->name('admin.rekap-absensi');
            Route::get('/rekap-absensi/export', [LaporanController::class, 'exportRekapAbsensi'])->name('admin.rekap-absensi.export');
        });

    });

    /*
    
     ----------GURU----------
    
    */
    Route::middleware([CheckRole::class . ':guru'])->prefix('guru')->group(function () {
        Route::get('/jadwal-saya', [JadwalPelajaranController::class, 'jadwalSaya'])->name('guru.jadwal-saya');

        
        Route::post('/ganti-password', [GuruController::class, 'updatePassword'])->name('guru.update-password');

        Route::get('/absensi-mapel/buka-sesi/{jadwalId}', [AbsensiMapelController::class, 'bukaSession'])->name('guru.absensi-mapel.buka-sesi');
        Route::post('/absensi-mapel/simpan-sesi/{jadwalId}', [AbsensiMapelController::class,'simpanSesi'])->name('guru.absensi-mapel.simpan-sesi');
        Route::get('/absensi-mapel/edit-sesi/{jadwalId}', [AbsensiMapelController::class, 'editSesi'])->name('guru.absensi-mapel.edit-sesi');
        Route::post('/absensi-mapel/update-sesi/{jadwalId}', [AbsensiMapelController::class, 'updateSesi'])->name('guru.absensi-mapel.update-sesi');

        Route::get('/absensi-mapel/rekap', [AbsensiMapelController::class, 'rekapKehadiran'])->name('guru.absensi-mapel.rekap');
        Route::get('/absensi-mapel/rekap/download', [AbsensiMapelController::class, 'downloadRekap'])->name('guru.absensi-mapel.download');
        Route::get('/absensi-mapel/rekap/cetak', [AbsensiMapelController::class, 'cetakRekap'])->name('guru.absensi-mapel.cetak');
        Route::get('/absensi-mapel/rekap-perkelas', [AbsensiMapelController::class, 'rekapPerKelas'])->name('guru.absensi-mapel.rekap-perkelas');
        Route::get('/absensi-mapel/notifikasi-ketidakhadiran', [AbsensiMapelController::class, 'notifikasiKetidakhadiran'])->name('guru.absensi-mapel.notifikasi-ketidakhadiran');

        Route::get('/laporan-alpha', [LaporanController::class, 'laporanAlphaGuru'])->name('guru.laporan-alpha');
    });

    /*
    ---------------------Wali Kelas---------------------

    */
    Route::middleware([CheckRole::class . ':wali_kelas'])->prefix('wali-kelas')->group(function () {

        Route::get('/monitor-absensi-harian', [AbsensiHarianController::class, 'viewWaliKelas'])->name('wali-kelas.monitor-absensi-harian');
        Route::get('/monitor-absensi-mapel', [AbsensiMapelController::class, 'viewWaliKelas'])->name('wali-kelas.monitor-absensi-mapel');

        Route::get('/siswa-alpha', [LaporanController::class, 'laporanAlphaWaliKelas'])->name('wali-kelas.siswa-alpha');
        Route::get('/rekap-kehadiran', [LaporanController::class, 'rekapKehadiranWaliKelas'])->name('wali-kelas.rekap-kehadiran');

        Route::get('/absensi-mapel/rekap-per-kelas', [AbsensiMapelController::class, 'rekapPerKelas'])->name('wali-kelas.absensi-mapel.rekap-per-kelas');

        Route::get('/siswa/{siswaId}', [SiswaController::class, 'showForWaliKelas'])->name('wali-kelas.siswa.show');
    });

/*
---------------------Orang Tua/Wali---------------------
*/
    Route::middleware([CheckRole::class . ':orang_tua'])
        ->prefix('orang-tua')
        ->name('orang-tua.')
        ->group(function () {

            Route::get('/statistik', [
                AbsensiHarianController::class,
                'statistikOrangTua'
            ])->name('statistik');

            Route::get('/absensi-mapel', [
                AbsensiMapelController::class,
                'viewOrangTua'
            ])->name('absensi-mapel');

            Route::get('/jadwal', [
                JadwalPelajaranController::class,
                'jadwalOrangTua'
            ])->name('jadwal');

            Route::get('/profil-anak', [
                SiswaController::class,
                'profilAnak'
            ])->name('profil-anak');
        });
    /*
    ---------------------Shared---------------------

    */
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/{id}/open', [NotifikasiController::class, 'open'])->name('notifikasi.open');
    Route::put('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.mark-read');

    Route::get('/absensi', function () {return redirect()->route('absensi-harian.index');})->name('absensi.index');

    Route::put('/profile/update', [UserController::class, 'updateProfil'])->name('profile.update');

    Route::prefix('catatan')->group(function () {
        Route::get('/', [CatatanController::class, 'index'])->name('catatan.index');
        Route::post('/store', [CatatanController::class, 'store'])->name('catatan.store');
    });
});


/*
---------------------Root---------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return Redirect::to('/dashboard');
    }

    return Redirect::to('/login');
})->name('home');