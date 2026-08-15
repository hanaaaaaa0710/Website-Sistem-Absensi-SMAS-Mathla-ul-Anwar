<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Notifikasi;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        /*
        |--------------------------------------------------------------------------
        | Halaman notifikasi Orang Tua
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'orang_tua') {
            $siswa = $user->siswa;

            if (!$siswa) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Data anak belum terhubung dengan akun orang tua/wali ini.');
            }

            $notifikasi = Notifikasi::query()
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);

            return view('notifikasi.orang-tua', compact(
                'notifikasi',
                'siswa'
            ));
        }

        /*
        |--------------------------------------------------------------------------
        | Hanya admin dan wali kelas yang dapat membuka halaman pengiriman
        |--------------------------------------------------------------------------
        */
        if (!in_array($user->role, ['admin', 'wali_kelas'], true)) {
            abort(403, 'Anda tidak memiliki izin mengakses halaman notifikasi.');
        }

        if ($user->role === 'wali_kelas') {
            $guru = $user->guru;

            if (!$guru || !$guru->kelas_wali_id) {
                return view('dashboard.error', [
                    'message' => 'Akun ini belum ditugaskan sebagai wali kelas.',
                ]);
            }

            $kelasWaliId = (int) $guru->kelas_wali_id;

            $kelasList = Kelas::query()
                ->whereKey($kelasWaliId)
                ->get();

            $siswaList = Siswa::query()
                ->with('kelas')
                ->where('kelas_id', $kelasWaliId)
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa')
                ->get();

            $userIds = $siswaList
                ->pluck('user_id')
                ->filter()
                ->values();

            $query = Notifikasi::query()
                ->with(['user.siswa.kelas'])
                ->whereIn('user_id', $userIds);
        } else {
            $kelasList = Kelas::query()
                ->orderBy('nama_kelas')
                ->get();

            $siswaList = Siswa::query()
                ->with('kelas')
                ->where('status', 'Aktif')
                ->orderBy('nama_siswa')
                ->get();

            $query = Notifikasi::query()
                ->with(['user.siswa.kelas']);
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('isi', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $statusNotifikasi = $request->input('status_notifikasi');

        if ($statusNotifikasi === 'sudah_dibaca') {
            $query->where('sudah_dibaca', true);
        } elseif ($statusNotifikasi === 'belum_dibaca') {
            $query->where('sudah_dibaca', false);
        }

        $statusWhatsapp = $request->input('wa_status');

        if (in_array($statusWhatsapp, [
            'Menunggu',
            'Dibuka',
            'Terkirim',
            'Nomor Tidak Tersedia',
        ], true)) {
            $query->where('wa_status', $statusWhatsapp);
        }

        /*
        |--------------------------------------------------------------------------
        | Statistik harus mengikuti hak akses pengguna
        |--------------------------------------------------------------------------
        */
        $statQuery = clone $query;

        $riwayat = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $riwayat->getCollection()->transform(function (Notifikasi $item) {
            $item->wa_preview = $item->wa_status
                ? $this->buatPesanWhatsapp($item)
                : null;

            return $item;
        });

        $totalNotifikasi = (clone $statQuery)->count();

        $notifikasiHariIni = (clone $statQuery)
            ->whereDate('created_at', today())
            ->count();

        $notifikasiOrangTua= (clone $statQuery)
            ->whereHas('user', fn ($q) => $q->where('role', 'orang_tua'))
            ->count();

        $belumDibaca = (clone $statQuery)
            ->where('sudah_dibaca', false)
            ->count();

        $whatsappMenunggu = (clone $statQuery)
            ->whereIn('wa_status', ['Menunggu', 'Dibuka'])
            ->count();

        $whatsappTerkirim = (clone $statQuery)
            ->where('wa_status', 'Terkirim')
            ->count();

        return view('notifikasi.index', compact(
            'kelasList',
            'siswaList',
            'riwayat',
            'totalNotifikasi',
            'notifikasiHariIni',
            'notifikasiOrangTua',
            'belumDibaca',
            'whatsappMenunggu',
            'whatsappTerkirim'
        ));
    }

    public function storeTemplate(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if (!in_array($user->role, ['admin', 'wali_kelas'], true)) {
            abort(403, 'Anda tidak memiliki izin mengirim notifikasi.');
        }

        $validated = $request->validate([
            'tujuan' => [
                'required',
                'in:semua_siswa,kelas_tertentu,siswa_tertentu',
            ],
            'kelas_id' => [
                'nullable',
                'integer',
                'exists:kelas,id',
            ],
            'siswa_id' => [
                'nullable',
                'integer',
                'exists:siswa,id',
            ],
            'jenis' => [
                'required',
                'in:Peringatan,Informasi,Prestasi',
            ],
            'judul' => [
                'required',
                'string',
                'max:255',
            ],
            'pesan' => [
                'required',
                'string',
                'max:2000',
            ],
            'tipe' => [
                'required',
                'in:info,success,warning,danger',
            ],
            'kirim_whatsapp' => [
                'nullable',
                'boolean',
            ],
        ]);

        if (
            $validated['tujuan'] === 'kelas_tertentu'
            && empty($validated['kelas_id'])
        ) {
            return back()
                ->withInput()
                ->with('error', 'Kelas harus dipilih.');
        }

        if (
            $validated['tujuan'] === 'siswa_tertentu'
            && empty($validated['siswa_id'])
        ) {
            return back()
                ->withInput()
                ->with('error', 'Siswa harus dipilih.');
        }

        $querySiswa = Siswa::query()
            ->with('kelas')
            ->where('status', 'Aktif')
            ->whereNotNull('user_id');

        /*
        |--------------------------------------------------------------------------
        | Pembatasan wali kelas
        |--------------------------------------------------------------------------
        */
        if ($user->role === 'wali_kelas') {
            $guru = $user->guru;

            if (!$guru || !$guru->kelas_wali_id) {
                return back()->with(
                    'error',
                    'Akun ini belum ditugaskan sebagai wali kelas.'
                );
            }

            $kelasWaliId = (int) $guru->kelas_wali_id;

            $querySiswa->where('kelas_id', $kelasWaliId);

            if ($validated['tujuan'] === 'siswa_tertentu') {
                $querySiswa->whereKey($validated['siswa_id']);
            }

            /*
             * Wali kelas tidak boleh memaksa kelas lain
             * melalui perubahan request dari browser.
             */
            if (
                $validated['tujuan'] === 'kelas_tertentu'
                && (int) $validated['kelas_id'] !== $kelasWaliId
            ) {
                abort(403, 'Anda hanya dapat mengirim ke kelas yang diampu.');
            }
        } else {
            /*
            |--------------------------------------------------------------------------
            | Admin
            |--------------------------------------------------------------------------
            */
            if ($validated['tujuan'] === 'kelas_tertentu') {
                $querySiswa->where(
                    'kelas_id',
                    $validated['kelas_id']
                );
            } elseif ($validated['tujuan'] === 'siswa_tertentu') {
                $querySiswa->whereKey($validated['siswa_id']);
            }
        }

        $siswaList = $querySiswa->get();

        if ($siswaList->isEmpty()) {
            return back()
                ->withInput()
                ->with('error', 'Tidak ada siswa yang sesuai dengan tujuan.');
        }

        $kirimWhatsapp = $request->boolean('kirim_whatsapp');

        $jumlahNotifikasi = 0;
        $jumlahWhatsapp = 0;
        $nomorTidakTersedia = 0;

        foreach ($siswaList as $siswa) {
            $statusPesan = match ($validated['jenis']) {
                'Peringatan' => 'Alpha',
                'Prestasi' => 'Prestasi',
                default => 'Informasi',
            };

            $namaOrtu = $siswa->nama_ortu ?: 'Bapak/Ibu Orang Tua/Wali';

            $isiPesan = str_replace(
                [
                    '[nama_ortu]',
                    '[nama_siswa]',
                    '[kelas]',
                    '[status]',
                    '[tanggal]',
                ],
                [
                    $namaOrtu,
                    $siswa->nama_siswa,
                    $siswa->kelas?->nama_kelas ?? '-',
                    $statusPesan,
                    now()->format('d-m-Y'),
                ],
                $validated['pesan']
            );

            $nomorWhatsapp = $kirimWhatsapp
                ? $this->formatNomorWhatsapp($siswa->no_hp_ortu)
                : null;

            $statusWhatsapp = null;

            if ($kirimWhatsapp) {
                if ($nomorWhatsapp) {
                    $statusWhatsapp = 'Menunggu';
                    $jumlahWhatsapp++;
                } else {
                    $statusWhatsapp = 'Nomor Tidak Tersedia';
                    $nomorTidakTersedia++;
                }
            }

            Notifikasi::query()->create([
                'user_id' => $siswa->user_id,
                'judul' => $validated['judul'],
                'isi' => $isiPesan,
                'tipe' => $validated['tipe'],
                'sudah_dibaca' => false,
                'wa_nomor' => $nomorWhatsapp,
                'wa_status' => $statusWhatsapp,
            ]);

            $jumlahNotifikasi++;
        }

        $pesanSukses = "{$jumlahNotifikasi} notifikasi berhasil dibuat.";

        if ($kirimWhatsapp) {
            $pesanSukses .=
                " {$jumlahWhatsapp} pesan WhatsApp siap dikirim.";

            if ($nomorTidakTersedia > 0) {
                $pesanSukses .=
                    " {$nomorTidakTersedia} siswa tidak memiliki nomor WhatsApp orang tua.";
            }
        }

        return redirect()
            ->route('notifikasi.index')
            ->with('success', $pesanSukses);
    }

    public function openWhatsapp(int $id): RedirectResponse
    {
        $notifikasi = $this->getNotifikasiUntukPengirim($id);

        if (!$notifikasi->wa_nomor) {
            return back()->with(
                'error',
                'Nomor WhatsApp orang tua tidak tersedia.'
            );
        }

        $notifikasi->update([
            'wa_status' => 'Dibuka',
            'wa_dibuka_at' => now(),
        ]);

        $pesanWhatsapp = $this->buatPesanWhatsapp($notifikasi);

        $url = 'https://wa.me/'
            . $notifikasi->wa_nomor
            . '?text='
            . rawurlencode($pesanWhatsapp);

        return redirect()->away($url);
    }

    public function confirmWhatsapp(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'konfirmasi_terkirim' => [
                'required',
                'accepted',
            ],
        ], [
            'konfirmasi_terkirim.accepted' =>
                'Centang konfirmasi bahwa pesan sudah dikirim melalui WhatsApp.',
        ]);

        $notifikasi = $this->getNotifikasiUntukPengirim($id);

        if (!$notifikasi->wa_dibuka_at) {
            return back()->with(
                'error',
                'Buka WhatsApp terlebih dahulu sebelum mengonfirmasi pengiriman.'
            );
        }

        $notifikasi->update([
            'wa_status' => 'Terkirim',
            'wa_terkirim_at' => now(),
            'wa_dikonfirmasi_oleh' => Auth::id(),
        ]);

        return back()->with(
            'success',
            'Pesan WhatsApp telah dikonfirmasi terkirim.'
        );
    }

    public function open(int $id): RedirectResponse
    {
        $notifikasi = Notifikasi::query()
            ->whereKey($id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$notifikasi->sudah_dibaca) {
            $notifikasi->update([
                'sudah_dibaca' => true,
            ]);
        }

        return redirect()->route('notifikasi.index', [
            'highlight' => $notifikasi->id,
        ]);
    }

    public function markAsRead(int $id): RedirectResponse
    {
        $notifikasi = Notifikasi::query()
            ->whereKey($id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notifikasi->update([
            'sudah_dibaca' => true,
        ]);

        return back()->with(
            'success',
            'Notifikasi sudah dibaca.'
        );
    }

    private function buatPesanWhatsapp(Notifikasi $notifikasi): string
    {
        $siswa = $notifikasi->user?->siswa;

        $namaOrtu  = $siswa?->nama_ortu ?: 'Bapak/Ibu Orang Tua/Wali';
        $namaSiswa = $siswa?->nama_siswa ?? '-';
        $kelas     = $siswa?->kelas?->nama_kelas ?? '-';

        if ($notifikasi->tipe === 'warning') {

            return implode("\n", [

                "Assalamu'alaikum Bapak/Ibu {$namaOrtu},",
                "",
                "Kami memberitahukan bahwa Ananda {$namaSiswa} dari kelas {$kelas} memperoleh status kehadiran yang memerlukan perhatian Bapak/Ibu.",
                "",
                "Silakan membuka menu Notifikasi pada akun Orang Tua/Wali untuk melihat informasi lengkap yang telah dikirim oleh sekolah.",
                "",
                "Apabila terdapat hal yang ingin dikonfirmasi, silakan menghubungi wali kelas atau guru mata pelajaran terkait.",
                "",
                "Terima kasih.",
                "",
                "Hormat kami,",
                "SMAS Mathla'ul Anwar"
            ]);
        }

        if ($notifikasi->tipe === 'success') {

            return implode("\n", [

                "Assalamu'alaikum Bapak/Ibu {$namaOrtu},",
                "",
                "Selamat.",
                "",
                "Ananda {$namaSiswa} dari kelas {$kelas} memperoleh informasi prestasi dari sekolah.",
                "",
                "Silakan membuka menu Notifikasi pada akun Orang Tua/Wali untuk melihat informasi selengkapnya.",
                "",
                "Terima kasih atas dukungan Bapak/Ibu.",
                "",
                "Hormat kami,",
                "SMAS Mathla'ul Anwar"
            ]);
        }

        return implode("\n", [

            "Assalamu'alaikum Bapak/Ibu {$namaOrtu},",
            "",
            "Kami menyampaikan informasi mengenai Ananda {$namaSiswa} dari kelas {$kelas}.",
            "",
            "Silakan membuka menu Notifikasi pada akun Orang Tua/Wali untuk melihat informasi lengkap yang telah dikirim oleh sekolah.",
            "",
            "Apabila terdapat hal yang perlu dikonfirmasi, Bapak/Ibu dapat menghubungi wali kelas atau guru mata pelajaran terkait.",
            "",
            "Terima kasih.",
            "",
            "Hormat kami,",
            "SMAS Mathla'ul Anwar"
        ]);
    }

    private function getNotifikasiUntukPengirim(int $id): Notifikasi
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if (!in_array($user->role, ['admin', 'wali_kelas'], true)) {
            abort(403, 'Anda tidak memiliki izin mengirim WhatsApp.');
        }

        $query = Notifikasi::query()
            ->with(['user.siswa.kelas'])
            ->whereKey($id);

        if ($user->role === 'wali_kelas') {
            $kelasWaliId = $user->guru?->kelas_wali_id;

            if (!$kelasWaliId) {
                abort(403, 'Anda belum ditugaskan sebagai wali kelas.');
            }

            $query->whereHas('user.siswa', function ($siswaQuery) use ($kelasWaliId) {
                $siswaQuery->where('kelas_id', $kelasWaliId);
            });
        }

        return $query->firstOrFail();
    }

    private function formatNomorWhatsapp(?string $nomor): ?string
    {
        if (!$nomor) {
            return null;
        }

        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        if (!$nomor) {
            return null;
        }

        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }

        if (!str_starts_with($nomor, '62')) {
            return null;
        }

        if (strlen($nomor) < 10 || strlen($nomor) > 15) {
            return null;
        }

        return $nomor;
    }
}