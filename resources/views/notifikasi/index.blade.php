@extends('layout.master')

@section('title', 'Notifikasi')

@section('content')
<style>
  .notif-page-card {
    border-radius: 20px;
    border: 1px solid #edf0f5;
    background: #fff;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
  }

  .notif-title {
    font-size: 24px;
    font-weight: 700;
    color: #25324b;
    margin-bottom: 4px;
  }

  .notif-subtitle {
    font-size: 14px;
    color: #8a94a6;
  }

  .summary-box {
    border-radius: 14px;
    padding: 14px 16px;
    border: 1px solid #edf0f5;
    background: #fff;
    min-height: 92px;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .summary-icon {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }

  .summary-green {
    background: #eefcf3;
  }

  .summary-green .summary-icon {
    background: #dcfce7;
    color: #16a34a;
  }

  .summary-blue {
    background: #eef6ff;
  }

  .summary-blue .summary-icon {
    background: #dbeafe;
    color: #2563eb;
  }

  .summary-yellow {
    background: #fff8e7;
  }

  .summary-yellow .summary-icon {
    background: #fef3c7;
    color: #d97706;
  }

  .summary-red {
    background: #fdecec;
  }

  .summary-red .summary-icon {
    background: #fee2e2;
    color: #dc2626;
  }

  .summary-number {
    font-size: 22px;
    font-weight: 700;
    line-height: 1.1;
  }

  .summary-label {
    font-size: 13px;
    color: #6b7280;
  }

  .filter-input,
  .filter-select {
    border-radius: 12px;
    min-height: 44px;
    border: 1px solid #e5eaf2;
    box-shadow: none !important;
  }

  .btn-template {
    border-radius: 12px;
    min-height: 44px;
    padding: 10px 18px;
    font-weight: 600;
  }

  .template-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
  }

  .template-success {
    background: #f0fbf4;
    border-color: #d8f0e0;
  }

  .template-fail {
    background: #fff4f4;
    border-color: #f7d7d7;
  }

  .template-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 10px;
  }

  .template-title {
    font-weight: 700;
    font-size: 15px;
    color: #25324b;
    margin-bottom: 0;
  }

  .timeline-wa{
    background:#f7fdf9;
    border:1px dashed #25D366;
    border-radius:10px;
    padding:10px;
  }

  .status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
    position: static !important;
}

  .notif-berhasil {
    background: #dcfce7;
    color: #15803d;
  }

  .notif-gagal {
    background: #fee2e2;
    color: #dc2626;
  }

  .notif-menunggu {
    background: #fef3c7;
    color: #d97706;
  }

  .template-meta {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 10px;
  }


  .template-body b {
    color: #1f2937;
  }

  .template-actions {
    border-top: 1px dashed rgba(0,0,0,0.08);
    padding-top: 12px;
  }

  .template-actions .form-select {
    min-height: 38px;
    border-radius: 10px;
    font-size: 13px;
  }

  .template-actions .btn {
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
  }

  .table-footer-clean {
    border-top: 1px solid #edf0f5;
    padding-top: 16px;
    margin-top: 18px;
  }

  .table-info-text {
    font-size: 13px;
    color: #8a94a6;
  }

  .table-pagination .pagination {
    margin: 0;
    display: flex;
    gap: 4px;
  }

  .table-pagination .page-link {
    border: none;
    border-radius: 10px;
    min-width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #495057;
    background: transparent;
  }

  .table-pagination .page-item.active .page-link {
    background-color: #eef3ff;
    color: #0d6efd;
    font-weight: 600;
  }

  .notif-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 24px;
    align-items: start;
  }

  .notif-grid-item {
    min-width: 0;
    align-self: start;
  }

  .template-card {
    width: 100%;
    height: auto !important;
    min-height: 0 !important;
    max-height: none !important;
    border-radius: 16px;
    border: 1px solid #edf0f5;
    box-sizing: border-box;
    overflow: hidden;
    padding: 20px;
    position: relative;
  }

  .template-body {
    width: 100%;
    font-size: 14px;
    line-height: 1.6;
    color: #374151;
  }

  .preview-wa {
    width: 100%;
    max-height: 260px;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 16px;
    margin: 16px 0;
    box-sizing: border-box;
    background: #f8fafc;
    border-left: 4px solid #25D366;
    border-radius: 10px;
    font-size: 13px;
  }

  .notifikasi-scroll {
    width: 100%;
    max-height: 180px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 8px;
    margin-bottom: 16px;
    box-sizing: border-box;
  }

  .btn-wa-compact {
    min-height: 42px;
    padding: 9px 14px;
    font-size: 13px;
  }

  @media (max-width: 991.98px) {
    .notif-grid {
      grid-template-columns: 1fr;
    }
  }

  .wa-info-box {
    width: 100%;
    display: grid;
    gap: 6px;
    font-size: 13px;
    color: #64748b;
    padding: 12px;
    box-sizing: border-box;
    border-radius: 10px;
    background: #fff;
    border: 1px solid #eef1f5;
  }

  .template-actions {
    position: static !important;
    width: 100%;
    margin-top: 16px;
  }

</style>


<div class="card notif-page-card">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
      <div>
        <div class="notif-title">Sistem Notifikasi</div>
        <div class="notif-subtitle">Kelola Pengiriman dan Riwayat Notifikasi Siswa</div>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="summary-box summary-green">
          <div class="summary-icon">
            <i class="ti ti-bell-check"></i>
          </div>
          <div>
            <div class="summary-number text-success">{{ $totalNotifikasi }}</div>
            <div class="summary-label">Total Notifikasi</div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="summary-box summary-blue">
          <div class="summary-icon">
            <i class="ti ti-chart-donut"></i>
          </div>
          <div>
            <div class="summary-number text-primary">{{ $notifikasiHariIni ?? 0 }}</div>
            <div class="summary-label">Notifikasi Hari Ini</div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="summary-box summary-yellow">
          <div class="summary-icon">
            <i class="ti ti-clock-hour-4"></i>
          </div>
          <div>
            <div class="summary-number text-warning">{{ $notifikasiSiswa ?? 0 }}</div>
            <div class="summary-label">Notifikasi untuk Siswa</div>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="summary-box summary-red">
          <div class="summary-icon">
            <i class="ti ti-alert-circle"></i>
          </div>
          <div>
            <div class="summary-number text-danger">{{ $belumDibaca ?? 0 }}</div>
            <div class="summary-label">Belum Dibaca</div>
          </div>
        </div>
      </div>
    </div>

    <form action="{{ route('notifikasi.index') }}" method="GET" id="filterNotifForm">
      <div class="row g-3 align-items-center mb-4">
        <div class="col-md-5">
          <input
            type="text"
            name="search"
            class="form-control filter-input"
            placeholder="Cari judul, isi, atau penerima..."
            value="{{ request('search') }}"
            id="searchNotif">
        </div>

        <div class="col-md-3">
          <select
            name="status_notifikasi"
            class="form-select filter-select"
            onchange="document.getElementById('filterNotifForm').submit()">
            <option value="">Semua Status</option>
            <option value="belum_dibaca" {{ request('status_notifikasi') == 'belum_dibaca' ? 'selected' : '' }}>
              Belum Dibaca
            </option>
            <option value="sudah_dibaca" {{ request('status_notifikasi') == 'sudah_dibaca' ? 'selected' : '' }}>
              Sudah Dibaca
            </option>
          </select>
        </div>

        <div class="col-md-4 text-md-end">
          <button type="button" class="btn btn-primary btn-template" data-bs-toggle="modal" data-bs-target="#templateModal">
            + Kirim Notifikasi
          </button>
        </div>
      </div>
    </form>

    <div class="notif-grid">
      @forelse($riwayat as $item)
        <div class="notif-grid-item">
          <div class="template-card {{ $item->sudah_dibaca ? 'template-success' : 'template-fail' }}">
            <div class="template-header">
              <div>
                <div class="template-title">{{ $item->judul }}</div>
                <div class="template-meta">
                  {{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                </div>
              </div>
            

              <span class="status-badge {{ $item->sudah_dibaca ? 'notif-berhasil' : 'notif-menunggu' }}">
                {{ $item->sudah_dibaca ? 'Sudah Dibaca' : 'Belum Dibaca' }}
              </span>
            </div>

            <div class="template-body">
              <b>Penerima:</b><br>
              {{ $item->user->name ?? '-' }}

              <hr>

              <b>Isi Notifikasi:</b><br>

              <div class="notifikasi-scroll mt-2">
              {{ $item->isi }}
          </div>

          @if($item->wa_status)

          <hr>

          <div class="mt-3">

              <div class="d-flex justify-content-between align-items-start">
                <div>

                    <strong>
                        <i class="ti ti-brand-whatsapp text-success"></i>
                        WhatsApp Orang Tua
                    </strong>

                    <div class="timeline-wa mt-2">

                    <small class="text-muted fw-semibold">

                    Progress Pengiriman

                    </small>

                    <div>

                    <i class="ti ti-circle-check text-warning"></i>

                    🟡 Pesan dibuat

                    </div>

                    @if(in_array($item->wa_status,['Dibuka','Terkirim']))

                    <div>

                    <i class="ti ti-circle-check text-primary"></i>

                    🔵 WhatsApp Dibuka

                    </div>

                    @endif

                    @if($item->wa_status=='Terkirim')

                    <div>
                    
                    <i class="ti ti-circle-check text-success"></i>

                    🟢 Pengiriman Dikonfirmasi

                    </div>

                    @endif

                    </div>

                    </div>

                @switch($item->wa_status)

                    @case('Menunggu')
                        <span class="badge bg-warning text-dark">
                            <i class="ti ti-clock"></i>
                            Menunggu
                        </span>
                        @break

                    @case('Dibuka')
                        <span class="badge bg-info">
                            <i class="ti ti-eye"></i>
                            WhatsApp Dibuka
                        </span>
                        @break

                    @case('Terkirim')
                        <span class="badge bg-success">
                            <i class="ti ti-circle-check"></i>
                            WhatsApp Terkirim
                        </span>
                        @break

                    @case('Nomor Tidak Tersedia')
                        <span class="badge bg-secondary">
                            <i class="ti ti-phone-off"></i>
                            Nomor Tidak Tersedia
                        </span>
                        @break

                @endswitch

            </div>

            @if($item->wa_nomor)
                <div class="wa-info-box mb-3">
                    <div>
                        <i class="ti ti-phone"></i>
                        <strong>Nomor WA:</strong>
                        {{ preg_replace('/^62/', '0', $item->wa_nomor) }}
                    </div>

                    <div>
                        <i class="ti ti-user"></i>
                        <strong>Orang Tua:</strong>
                        {{ $item->user?->siswa?->nama_ortu ?? '-' }}
                    </div>

                    <div>
                        <i class="ti ti-user"></i>
                        <strong>Siswa:</strong>
                        {{ $item->user?->siswa?->nama_siswa ?? '-' }}
                    </div>
                </div>
            @endif

            <div class="preview-wa mb-3">

                  <small class="fw-semibold text-muted">
                    <i class='ti ti-brand-whatsapp text-success'></i>
                     Preview Pesan WhatsApp Orang Tua/Wali
                  </small>

                  <hr class="my-2">

                  <div style="white-space: pre-line">
                      {{ $item->wa_preview ?? '-' }}
                  </div>

                </div>
            </div>


            @if($item->wa_status=='Menunggu')

                <a href="{{ auth()->user()->role=='admin'
                        ? route('admin.notifikasi.whatsapp.open',$item->id)
                        : route('wali-kelas.notifikasi.whatsapp.open',$item->id) }}"
                    class="btn btn-success btn-sm w-100 btn-open-wa btn-wa-compact">

                    <i class="ti ti-brand-whatsapp me-2"></i>
                    Kirim via WhatsApp

                 </a>

            @elseif($item->wa_status=='Dibuka')

                <div class="alert alert-info py-2 px-3 mt-2 mb-2">
                @if($item->wa_dibuka_at)
                
                <div class="small text-muted mb-2">

                <i class="ti ti-clock"></i>

                WhatsApp dibuka :

                {{ $item->wa_dibuka_at->format('d M Y H:i') }}

                </div>

                @endif

                    <i class="ti ti-info-circle"></i>

                    WhatsApp berhasil dibuka.

                    Silakan kirim pesan kepada orang tua/wali melalui aplikasi WhatsApp.

                    Apabila pesan telah berhasil dikirim,
                    lakukan konfirmasi pengiriman pada sistem.

                </div>

                <form
                    method="POST"
                    action="{{ auth()->user()->role=='admin'
                        ? route('admin.notifikasi.whatsapp.confirm',$item->id)
                        : route('wali-kelas.notifikasi.whatsapp.confirm',$item->id) }}">

                    @csrf
                    @method('PUT')

                    <div class="form-check mb-2">

                        <input
                            type="checkbox"
                            class="form-check-input"
                            name="konfirmasi_terkirim"
                            value="1"
                            id="wa{{ $item->id }}"
                            required>

                        <label
                            class="form-check-label"
                            for="wa{{ $item->id }}">

                            Saya memastikan pesan telah dikirim melalui WhatsApp

                        </label>

                    </div>

                    <button
                        class="btn btn-primary btn-sm w-100">

                        <i class="ti ti-check"></i>

                        Konfirmasi Terkirim

                    </button>

                </form>

            @elseif($item->wa_status=='Terkirim')

                <div class="alert alert-success py-2 px-3 mt-2">

                    <div class="fw-semibold mb-2">
                        <i class="ti ti-circle-check"></i>
                        Detail Pengiriman
                    </div>

                    <div class="small text-muted mb-2">
                        <i class="ti ti-calendar"></i>
                        {{ optional($item->wa_terkirim_at)->format('d M Y') }}

                        &nbsp;&nbsp;

                        <i class="ti ti-clock"></i>
                        {{ optional($item->wa_terkirim_at)->format('H:i') }} WIB
                    </div>

                    <div>
                        Status pengiriman telah dikonfirmasi.
                    </div>

                </div>

            @elseif($item->wa_status=='Nomor Tidak Tersedia')

                <div class="alert alert-secondary py-2 px-3 mt-2">

                  <i class="ti ti-alert-circle"></i>

                  Nomor WhatsApp orang tua belum tersedia.

                </div>

            @endif

          </div>

          @endif

          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="text-center text-muted py-4">
            Data notifikasi belum ada.
          </div>
        </div>
      @endforelse
    </div>

    <div class="table-footer-clean d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div class="table-info-text">
        Menampilkan {{ $riwayat->firstItem() ?? 0 }} - {{ $riwayat->lastItem() ?? 0 }} dari {{ $riwayat->total() }} data
      </div>

      <div class="table-pagination">
        {{ $riwayat->onEachSide(1)->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="templateModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:16px;">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Kirim Notifikasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form
        method="POST"
        action="{{ auth()->user()->role === 'admin'
            ? route('admin.notifikasi.storeTemplate')
            : route('wali-kelas.notifikasi.storeTemplate') }}">

        @csrf
        <div class="modal-body">
          <div class="row g-3">

            <div class="col-md-6">
              <label class="form-label">Tujuan Pengiriman</label>
              <select name="tujuan" id="tujuanNotif" class="form-select" required>
                <option value="">Pilih tujuan</option>
                <option value="semua_siswa">Semua Siswa</option>
                <option value="kelas_tertentu">Kelas Tertentu</option>
                <option value="siswa_tertentu">Siswa Tertentu</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Jenis Notifikasi</label>
              <select name="jenis" id="jenisNotif" class="form-select" required>
                <option value="">Pilih jenis</option>
                <option value="Informasi">Informasi</option>
                <option value="Peringatan">Peringatan</option>
                <option value="Prestasi">Prestasi</option>
              </select>
            </div>

            <div class="col-md-6 d-none" id="kelasWrapper">
              <label class="form-label">Pilih Kelas</label>
              <select name="kelas_id" class="form-select">
                <option value="">Pilih kelas</option>
                @foreach($kelasList as $kelas)
                  <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6 d-none" id="siswaWrapper">
              <label class="form-label">Pilih Siswa</label>

              <input
                  type="text"
                  id="searchSiswa"
                  class="form-control mb-2"
                  placeholder="Cari nama siswa atau kelas...">

              <select
                  name="siswa_id"
                  id="siswaSelect"
                  class="form-select">

                  <option value="">Pilih siswa</option>

                  @foreach($siswaList as $siswa)
                      <option
                          value="{{ $siswa->id }}"
                          data-nama="{{ strtolower($siswa->nama_siswa) }}"
                          data-kelas="{{ strtolower($siswa->kelas->nama_kelas ?? '') }}">

                          {{ $siswa->nama_siswa }} — {{ $siswa->kelas->nama_kelas ?? '-' }}
                      </option>
                  @endforeach
              </select>

              <small id="hasilCariSiswa" class="text-muted"></small>
            </div>


            <div class="col-12">
              <label class="form-label">Judul Notifikasi</label>
              <input type="text" name="judul" id="judulNotif" class="form-control" required>
            </div>

            <div class="col-12">
              <label class="form-label">Isi Notifikasi</label>
              <textarea name="pesan" id="pesanNotif" class="form-control" rows="8" required></textarea>

              <small class="text-muted d-block mt-2">
              <i class="ti ti-info-circle"></i>
              Pesan ini akan tampil pada akun siswa.
              Apabila opsi <strong>WhatsApp Orang Tua/Wali</strong> dipilih,
              sistem akan membuat format pesan WhatsApp khusus secara otomatis.
              </small>
            </div>

            <div class="col-12">
                <div class="form-check mt-2">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="kirim_whatsapp"
                        value="1"
                        id="kirim_whatsapp">

                    <label
                        class="form-check-label"
                        for="kirim_whatsapp">
                        Kirim juga ke WhatsApp Orang Tua/Wali
                    </label>
                </div>
            </div>

            <input type="hidden" name="tipe" id="tipeNotif" value="info">
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Kirim Notifikasi</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchNotif');
    const filterForm = document.getElementById('filterNotifForm');

    const tujuanNotif = document.getElementById('tujuanNotif');
    const kelasWrapper = document.getElementById('kelasWrapper');
    const siswaWrapper = document.getElementById('siswaWrapper');
    const kelasSelect = kelasWrapper?.querySelector('select[name="kelas_id"]');
    const siswaSelect = document.getElementById('siswaSelect');
    const searchSiswa = document.getElementById('searchSiswa');
    const hasilCariSiswa = document.getElementById('hasilCariSiswa');

    const judulNotif = document.getElementById('judulNotif');
    const jenisNotif = document.getElementById('jenisNotif');
    const pesanNotif = document.getElementById('pesanNotif');
    const tipeNotif = document.getElementById('tipeNotif');

    if (searchInput && filterForm) {
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                filterForm.submit();
            }
        });
    }

    const daftarSiswa = siswaSelect
        ? Array.from(siswaSelect.options).map(function (option) {
            return {
                value: option.value,
                text: option.textContent.trim(),
                nama: (option.dataset.nama || '').toLowerCase(),
                kelas: (option.dataset.kelas || '').toLowerCase()
            };
        })
        : [];

    function renderPilihanSiswa(keyword = '') {
        if (!siswaSelect) return;

        const kataKunci = keyword.toLowerCase().trim();
        const nilaiTerpilih = siswaSelect.value;
        let jumlahHasil = 0;

        siswaSelect.innerHTML = '';

        daftarSiswa.forEach(function (siswa) {
            const cocok =
                siswa.value === '' ||
                siswa.nama.includes(kataKunci) ||
                siswa.kelas.includes(kataKunci) ||
                siswa.text.toLowerCase().includes(kataKunci);

            if (!cocok) return;

            const option = document.createElement('option');
            option.value = siswa.value;
            option.textContent = siswa.text;

            if (siswa.value === nilaiTerpilih) {
                option.selected = true;
            }

            siswaSelect.appendChild(option);

            if (siswa.value !== '') jumlahHasil++;
        });

        if (hasilCariSiswa) {
            hasilCariSiswa.textContent = kataKunci
                ? jumlahHasil + ' siswa ditemukan'
                : '';
        }
    }

    function toggleTargetField() {
        if (!tujuanNotif || !kelasWrapper || !siswaWrapper) return;

        kelasWrapper.classList.add('d-none');
        siswaWrapper.classList.add('d-none');

        if (kelasSelect) {
            kelasSelect.required = false;
            kelasSelect.value = '';
        }

        if (siswaSelect) {
            siswaSelect.required = false;
            siswaSelect.value = '';
        }

        if (searchSiswa) searchSiswa.value = '';
        if (hasilCariSiswa) hasilCariSiswa.textContent = '';

        if (tujuanNotif.value === 'kelas_tertentu') {
            kelasWrapper.classList.remove('d-none');
            if (kelasSelect) kelasSelect.required = true;
        } else if (tujuanNotif.value === 'siswa_tertentu') {
            siswaWrapper.classList.remove('d-none');
            if (siswaSelect) siswaSelect.required = true;
            renderPilihanSiswa('');
        }
    }

    if (searchSiswa && siswaSelect) {
        searchSiswa.addEventListener('input', function () {
            renderPilihanSiswa(this.value);
        });
    }

    const templateSiswa = {
      Informasi: {
          judul: 'Informasi Sekolah',
          isi: `Assalamu'alaikum Bapak/Ibu [nama_ortu],

  Kami menyampaikan informasi dari SMAS Mathla'ul Anwar mengenai Ananda [nama_siswa] yang merupakan siswa kelas [kelas].

  Informasi yang disampaikan pada tanggal [tanggal] memiliki kategori:
  [status].

  Mohon Bapak/Ibu memperhatikan informasi tersebut dan mendampingi Ananda apabila diperlukan. Jika terdapat hal yang ingin dikonfirmasi, silakan menghubungi wali kelas atau guru mata pelajaran terkait.

  Terima kasih.

  Hormat kami,
  SMAS Mathla'ul Anwar`
      },

      Peringatan: {
          judul: 'Peringatan Kehadiran',
          isi: `Assalamu'alaikum Bapak/Ibu [nama_ortu],

  Kami memberitahukan bahwa Ananda [nama_siswa] dari kelas [kelas] memperoleh status kehadiran:

  [status]

  Tanggal:
  [tanggal]

  Mohon Bapak/Ibu dapat memberikan perhatian kepada Ananda serta melakukan konfirmasi kepada wali kelas apabila terdapat kendala atau kesalahan data.

  Terima kasih.

  Hormat kami,
  SMAS Mathla'ul Anwar`
      },

      Prestasi: {
          judul: 'Prestasi Siswa',
          isi: `Assalamu'alaikum Bapak/Ibu [nama_ortu],

  Selamat.

  Ananda [nama_siswa] dari kelas [kelas] memperoleh prestasi yang membanggakan.

  Tanggal:
  [tanggal]

  Semoga prestasi tersebut menjadi motivasi bagi Ananda untuk terus berkembang dan berprestasi.

  Terima kasih atas dukungan Bapak/Ibu.

  Hormat kami,
  SMAS Mathla'ul Anwar`
      }
  };

    function setTemplate() {
        if (!jenisNotif || !judulNotif || !pesanNotif || !tipeNotif) return;

        const jenis = jenisNotif.value;
        if (!jenis || !templateSiswa[jenis]) return;

        judulNotif.value = templateSiswa[jenis].judul;
        pesanNotif.value = templateSiswa[jenis].isi;

        if (jenis === 'Peringatan') {
            tipeNotif.value = 'warning';
        } else if (jenis === 'Prestasi') {
            tipeNotif.value = 'success';
        } else {
            tipeNotif.value = 'info';
        }
    }

    if (tujuanNotif) tujuanNotif.addEventListener('change', toggleTargetField);
    if (jenisNotif) jenisNotif.addEventListener('change', setTemplate);

    toggleTargetField();
});

document.querySelectorAll('.btn-open-wa').forEach(function(btn){

    btn.addEventListener('click', function(e){

        e.preventDefault();

        const url = this.href;

        Swal.fire({

            icon:'info',
            title:'Membuka WhatsApp',

            text:'Sistem sedang menyiapkan pesan WhatsApp untuk dikirim kepada orang tua/wali siswa.',

            timer:1200,

            showConfirmButton:false,

            allowOutsideClick:false

        });

        setTimeout(function(){

            window.open(url,'_blank');

            setTimeout(() => {

            window.location.href = window.location.href;

            },300);

        }, 1200);

    });

});
</script>
@endsection
