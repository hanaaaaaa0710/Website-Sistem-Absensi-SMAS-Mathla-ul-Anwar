@extends('layout.master')

@section('title', 'Absensi Siswa')

@section('content')
<style>
  .absensi-card {
    border-radius: 20px;
    border: 1px solid #edf0f5;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    background: #fff;
  }

  .tab-link {
    text-decoration: none;
    font-weight: 600;
    color: #8a94a6;
    padding-bottom: 10px;
  }

  .tab-link.active {
    color: #0d6efd;
    border-bottom: 2px solid #0d6efd;
  }

  .scan-box {
    text-align: center;
    padding: 25px 20px 10px;
  }

  .scan-image-wrap {
    width: 260px;
    margin: 0 auto 20px;
    position: relative;
  }

  .scan-image {
    width: 260px;
    height: 300px;
    object-fit: cover;
    border-radius: 18px;
    border: 2px solid #e5eaf2;
    box-shadow: 0 10px 25px rgba(0,0,0,0.06);
    background: #fff;
  }

  .scan-frame {
    position: absolute;
    inset: 10px;
    border: 2px dashed rgba(13, 110, 253, 0.6);
    border-radius: 14px;
    pointer-events: none;
  }

  .scan-line {
    position: absolute;
    left: 18px;
    right: 18px;
    top: 30px;
    height: 3px;
    background: linear-gradient(90deg, transparent, #0d6efd, transparent);
    box-shadow: 0 0 12px rgba(13, 110, 253, 0.8);
    border-radius: 999px;
    opacity: 0;
    pointer-events: none;
  }

  .scan-image-wrap.scanning .scan-line {
    opacity: 1;
    animation: scanMove 2s linear infinite;
  }

  @keyframes scanMove {
    0% {
      top: 25px;
    }
    50% {
      top: 150px;
    }
    100% {
      top: 260px;
    }
  }

  .scan-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #eef4ff;
    color: #0d6efd;
    border-radius: 999px;
    padding: 8px 14px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 16px;
  }

  .scan-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #0d6efd;
    animation: pulseDot 1s infinite;
  }

  @keyframes pulseDot {
    0% { opacity: .3; transform: scale(.8); }
    50% { opacity: 1; transform: scale(1); }
    100% { opacity: .3; transform: scale(.8); }
  }

  .mini-stat {
    border-radius: 14px;
    padding: 16px;
    border: 1px solid #edf0f5;
    background: #fff;
    text-align: center;
    min-width: 150px;
  }

  .mini-stat.blue {
    background: #eef6ff;
    border-color: #cfe4ff;
  }

  .mini-stat.green {
    background: #eefcf3;
    border-color: #cdeed8;
  }

  .mini-stat.yellow {
    background: #fff8e7;
    border-color: #f7e2a5;
  }

  .scan-result-box {
    max-width: 520px;
    margin: 0 auto 20px;
    border-radius: 14px;
    padding: 14px 16px;
    font-weight: 600;
    display: none;
  }

  .scan-result-box.show {
    display: block;
  }

  .scan-result-success {
    background: #eaf8ef;
    color: #1f9d57;
    border: 1px solid #cdeed8;
  }

  .scan-result-fail {
    background: #fdecec;
    color: #dc3545;
    border: 1px solid #f3c6cb;
  }

  .status-last-box {
    border: 1px solid #edf0f5;
    border-radius: 14px;
    padding: 14px;
    background: #f9fbff;
  }

  .filter-grid {
    display: grid;
    grid-template-columns: 1.3fr 1fr 1fr auto;
    gap: 12px;
    align-items: center;
  }

  .filter-input,
  .filter-select {
    border-radius: 12px;
    min-height: 46px;
    border: 1px solid #e5eaf2;
    box-shadow: none !important;
  }

  .btn-add {
    border-radius: 12px;
    min-height: 46px;
    padding: 10px 18px;
    font-weight: 600;
    white-space: nowrap;
  }

  .table-wrapper {
    margin-top: 18px;
    max-height: 520px;
    overflow-y: auto;
    overflow-x: auto;
    border-radius: 16px;
  }

  .custom-table {
    min-width: 1150px;
    margin-bottom: 0;
  }

  .custom-table thead th {
    position: sticky;
    top: 0;
    z-index: 3;
    background-color: #2f3b52 !important;
    color: #fff !important;
    font-weight: 600;
    border-color: #43506a !important;
    padding: 16px 18px;
    white-space: nowrap;
  }

  .custom-table tbody td {
    padding: 16px 18px;
    vertical-align: middle;
    border-color: #edf0f5;
  }

  .status-badge {
    display: inline-block;
    padding: 7px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
  }

  .status-hadir { background: #eaf8ef; color: #1f9d57; }
  .status-izin, .status-sakit { background: #fff4db; color: #d4a017; }
  .status-alpha { background: #fdecec; color: #dc3545; }

  .notif-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
  }

  .notif-berhasil { background: #eaf8ef; color: #1f9d57; }
  .notif-gagal { background: #fdecec; color: #dc3545; }
  .notif-menunggu { background: #eef3ff; color: #0d6efd; }

  @media (max-width: 992px) {
    .filter-grid {
      grid-template-columns: 1fr;
    }
  }

  .scan-status-panel {
  max-width: 620px;
  margin: 0 auto 20px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
}

.scan-indicator {
  border-radius: 14px;
  border: 1px solid #edf0f5;
  background: #fff;
  padding: 12px;
  text-align: center;
}

.scan-indicator-label {
  font-size: 12px;
  color: #8a94a6;
  margin-bottom: 4px;
}

.scan-indicator-value {
  font-size: 13px;
  font-weight: 700;
}

.indicator-good {
  color: #16a34a;
}

.indicator-warn {
  color: #d97706;
}

.indicator-bad {
  color: #dc2626;
}

.scan-hint-box {
  max-width: 620px;
  margin: 0 auto 20px;
  border-radius: 14px;
  background: #f8fbff;
  border: 1px solid #dbeafe;
  padding: 12px 16px;
  color: #37517e;
  font-size: 13px;
}

@media (max-width: 768px) {
  .scan-status-panel {
    grid-template-columns: 1fr 1fr;
  }
}
</style>

<div class="card absensi-card">
  <div class="card-body p-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
      <div>
        <h3 class="fw-bold mb-1">Data Absensi</h3>
        <p class="text-muted mb-0">Manajemen data absensi siswa</p>
      </div>

      <a href="{{ route('absensi.create') }}" class="btn btn-primary btn-add">
        Tambah Absensi
      </a>
    </div>

    <div class="d-flex gap-4 mb-4 border-bottom pb-2">
      <a href="{{ route('absensi.index', ['tab' => 'scan']) }}"
         class="tab-link {{ request('tab', 'scan') == 'scan' ? 'active' : '' }}">
        Scan Absensi
      </a>

      <a href="{{ route('absensi.index', ['tab' => 'riwayat']) }}"
         class="tab-link {{ request('tab') == 'riwayat' ? 'active' : '' }}">
        Riwayat Absensi
      </a>
    </div>

    @if(session('sukses'))
      <div class="alert alert-success">{{ session('sukses') }}</div>
    @endif

    @if(request('tab', 'scan') == 'scan')
      <div class="scan-box">
        <h5 class="fw-bold mb-2">Scan Wajah Siswa</h5>
        <p class="text-muted mb-3">Posisikan wajah di dalam frame untuk simulasi absensi</p>

       <div id="scanBadge" class="scan-badge">
  <span class="scan-dot"></span>
  Siap untuk scanning
</div>

<div class="scan-hint-box" id="scanHintBox">
  Arahkan wajah lurus ke kamera, pastikan pencahayaan cukup, dan posisikan wajah di dalam frame.
</div>

<div class="scan-status-panel">
  <div class="scan-indicator">
    <div class="scan-indicator-label">Posisi</div>
    <div class="scan-indicator-value indicator-warn" id="indikatorPosisi">Belum dicek</div>
  </div>

  <div class="scan-indicator">
    <div class="scan-indicator-label">Jarak</div>
    <div class="scan-indicator-value indicator-warn" id="indikatorJarak">Belum dicek</div>
  </div>

  <div class="scan-indicator">
    <div class="scan-indicator-label">Cahaya</div>
    <div class="scan-indicator-value indicator-warn" id="indikatorCahaya">Belum dicek</div>
  </div>

  <div class="scan-indicator">
    <div class="scan-indicator-label">Status</div>
    <div class="scan-indicator-value indicator-warn" id="indikatorStatus">Siap scan</div>
  </div>
</div>

<div id="scanResult" class="scan-result-box"></div>

        <div id="scanWrap" class="scan-image-wrap">
          <img src="{{ asset('admin/assets/images/profile/user-1.jpg') }}" class="scan-image" alt="scan wajah">
          <div class="scan-frame"></div>
          <div class="scan-line"></div>
        </div>

        <div class="mb-3">
          <h5 class="fw-bold mb-1">{{ $scanSiswa->nama_siswa ?? 'Siswa Tidak Ditemukan' }}</h5>
          <p class="text-muted mb-0">{{ $scanSiswa->kelas->nama_kelas ?? '-' }}</p>
        </div>

        <div class="d-flex justify-content-center flex-wrap gap-3 mb-4">
          <div class="mini-stat blue">
            <div class="text-primary fw-bold fs-4">{{ $totalTerdeteksi }}</div>
            <small class="text-muted">Terdeteksi</small>
          </div>

          <div class="mini-stat green">
            <div class="text-success fw-bold fs-4">{{ $waktuScan }}</div>
            <small class="text-muted">Waktu Scan</small>
          </div>

          <div class="mini-stat yellow">
            <div class="text-warning fw-bold fs-4">{{ $akurasiScan }}%</div>
            <small class="text-muted">Akurasi</small>
          </div>
        </div>

        @if($scanSiswa)
        <form id="scanForm" action="{{ route('absensi.scanHadir') }}" method="POST" class="d-flex justify-content-center gap-2 flex-wrap mb-4">
          @csrf
          <input type="hidden" name="siswa_id" value="{{ $scanSiswa->id }}">
          <button type="button" id="btnMulaiScan" class="btn btn-outline-primary btn-add">
            Mulai Scan
          </button>
          <button type="submit" id="btnTandaiHadir" class="btn btn-primary btn-add" disabled>
            Tandai Hadir
          </button>
        </form>
        @endif

        <div class="status-last-box text-start">
          <div class="fw-semibold mb-3">Status Terakhir</div>
          <div class="d-flex flex-wrap gap-2">
            @forelse($statusTerakhir as $item)
              <span class="badge bg-success-subtle text-success">
                {{ $item->status }} - {{ $item->siswa->nama_siswa ?? '-' }} ({{ $item->jam_masuk ?? '-' }})
              </span>
            @empty
              <span class="text-muted">Belum ada data absensi hari ini.</span>
            @endforelse
          </div>
        </div>
      </div>
    @else
      <form action="{{ route('absensi.index') }}" method="GET" id="filterAbsensiForm">
        <input type="hidden" name="tab" value="riwayat">

        <div class="filter-grid">
          <input
            type="text"
            name="search"
            class="form-control filter-input"
            placeholder="Cari nama siswa"
            value="{{ request('search') }}"
            id="searchAbsensi">

          <input
            type="date"
            name="tanggal"
            class="form-control filter-input"
            value="{{ request('tanggal') }}"
            onchange="document.getElementById('filterAbsensiForm').submit()">

          <select
            name="status"
            class="form-select filter-select"
            onchange="document.getElementById('filterAbsensiForm').submit()">
            <option value="">Semua Status</option>
            <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
            <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
            <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
            <option value="Alpha" {{ request('status') == 'Alpha' ? 'selected' : '' }}>Alpha</option>
            <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
          </select>

          <a href="{{ route('absensi.index', ['tab' => 'riwayat']) }}" class="btn btn-light border btn-add">
            Reset
          </a>
        </div>
      </form>

      <div class="table-wrapper">
        <table class="table custom-table align-middle">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Siswa</th>
              <th>Tanggal</th>
              <th>Jam Masuk</th>
              <th>Status</th>
              <th>Notifikasi</th>
              <th>Keterangan</th>
              <th style="min-width: 180px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($data_absensi as $no => $item)
            <tr>
              <td>{{ $data_absensi->firstItem() + $no }}</td>
              <td>{{ $item->siswa->nama_siswa ?? '-' }}</td>
              <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
              <td>{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
              <td>
                <span class="status-badge
                  {{ $item->status == 'Hadir' ? 'status-hadir' : '' }}
                  {{ in_array($item->status, ['Izin', 'Sakit']) ? 'status-izin' : '' }}
                  {{ $item->status == 'Alpha' ? 'status-alpha' : '' }}
                  {{ $item->status == 'Terlambat' ? 'status-terlambat' : '' }}">
                  {{ $item->status }}
                </span>
              </td>
              <td>
                <span class="status-badge
                  {{ $item->status_notifikasi == 'Berhasil' ? 'notif-berhasil' : '' }}
                  {{ $item->status_notifikasi == 'Gagal' ? 'notif-gagal' : '' }}
                  {{ $item->status_notifikasi == 'Menunggu' ? 'notif-menunggu' : '' }}">
                  {{ $item->status_notifikasi }}
                </span>
              </td>
              <td>
                @if($item->keterangan)
                  {{ $item->keterangan }}
                @elseif($item->status == 'Hadir')
                  Hadir tepat waktu
                @elseif($item->status == 'Izin')
                  Izin tidak masuk
                @elseif($item->status == 'Sakit')
                  Sakit
                @else
                  Tidak hadir tanpa keterangan
                @endif
            </td>
              <td>
                <div class="d-flex gap-2">
                  <a href="{{ route('absensi.edit', $item->id) }}" class="btn btn-warning text-white btn-sm">Edit</a>
                  <form action="{{ route('absensi.delete', $item->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center text-muted py-4">Data absensi belum ada.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3 pt-3 border-top">
        <div class="text-muted small">
          Menampilkan {{ $data_absensi->firstItem() ?? 0 }} - {{ $data_absensi->lastItem() ?? 0 }} dari {{ $data_absensi->total() }} data
        </div>

        <div>
          {{ $data_absensi->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
      </div>
    @endif
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchAbsensi');
    const form = document.getElementById('filterAbsensiForm');

    if (searchInput && form) {
      searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
          e.preventDefault();
          form.submit();
        }
      });
    }

    const scanWrap = document.getElementById('scanWrap');
    const scanBadge = document.getElementById('scanBadge');
    const scanResult = document.getElementById('scanResult');
    const btnMulaiScan = document.getElementById('btnMulaiScan');
    const btnTandaiHadir = document.getElementById('btnTandaiHadir');

    const indikatorPosisi = document.getElementById('indikatorPosisi');
    const indikatorJarak = document.getElementById('indikatorJarak');
    const indikatorCahaya = document.getElementById('indikatorCahaya');
    const indikatorStatus = document.getElementById('indikatorStatus');
    const scanHintBox = document.getElementById('scanHintBox');

    function setIndicator(el, text, type) {
      if (!el) return;
      el.textContent = text;
      el.className = 'scan-indicator-value';

      if (type === 'good') el.classList.add('indicator-good');
      if (type === 'warn') el.classList.add('indicator-warn');
      if (type === 'bad') el.classList.add('indicator-bad');
    }

    if (btnMulaiScan && scanWrap && scanBadge && scanResult && btnTandaiHadir) {
      btnMulaiScan.addEventListener('click', function () {
        scanWrap.classList.add('scanning');
        scanResult.className = 'scan-result-box';
        scanResult.innerHTML = '';
        btnTandaiHadir.disabled = true;

        scanBadge.innerHTML = '<span class="scan-dot"></span> Scanning wajah...';
        scanHintBox.innerHTML = 'Sistem sedang menganalisis posisi wajah, jarak kamera, dan pencahayaan.';

        setIndicator(indikatorPosisi, 'Menganalisis...', 'warn');
        setIndicator(indikatorJarak, 'Menganalisis...', 'warn');
        setIndicator(indikatorCahaya, 'Menganalisis...', 'warn');
        setIndicator(indikatorStatus, 'Scanning...', 'warn');

        setTimeout(() => {
          scanWrap.classList.remove('scanning');

          const kondisi = [
            {
              hasil: 'berhasil',
              posisi: ['Pas', 'good'],
              jarak: ['Ideal', 'good'],
              cahaya: ['Cukup', 'good'],
              status: ['Terdeteksi', 'good'],
              badge: 'Wajah terdeteksi',
              hint: 'Wajah berada pada posisi ideal dan siap disimpan sebagai absensi.',
              message: 'Scan berhasil. Wajah cocok dengan data siswa. Klik <b>Tandai Hadir</b> untuk menyimpan absensi.'
            },
            {
              hasil: 'tidak_terdeteksi',
              posisi: ['Tidak ditemukan', 'bad'],
              jarak: ['Tidak terbaca', 'bad'],
              cahaya: ['Tidak terbaca', 'bad'],
              status: ['Gagal', 'bad'],
              badge: 'Wajah tidak terdeteksi',
              hint: 'Pastikan wajah terlihat jelas di area frame kamera.',
              message: 'Wajah tidak terdeteksi. Pastikan wajah berada di dalam frame kamera.'
            },
            {
              hasil: 'terlalu_jauh',
              posisi: ['Pas', 'good'],
              jarak: ['Terlalu jauh', 'bad'],
              cahaya: ['Cukup', 'good'],
              status: ['Perlu penyesuaian', 'warn'],
              badge: 'Posisi wajah terlalu jauh',
              hint: 'Silakan mendekat sedikit ke kamera agar sistem bisa membaca wajah dengan jelas.',
              message: 'Wajah terlalu jauh dari kamera. Silakan mendekat agar sistem bisa mengenali wajah dengan jelas.'
            },
            {
              hasil: 'terlalu_dekat',
              posisi: ['Sebagian wajah', 'warn'],
              jarak: ['Terlalu dekat', 'bad'],
              cahaya: ['Cukup', 'good'],
              status: ['Perlu penyesuaian', 'warn'],
              badge: 'Posisi wajah terlalu dekat',
              hint: 'Mundur sedikit sampai seluruh wajah masuk ke dalam frame.',
              message: 'Wajah terlalu dekat dengan kamera. Silakan mundur sedikit agar seluruh wajah masuk ke dalam frame.'
            },
            {
              hasil: 'tidak_pas',
              posisi: ['Tidak lurus', 'bad'],
              jarak: ['Ideal', 'good'],
              cahaya: ['Cukup', 'good'],
              status: ['Perlu penyesuaian', 'warn'],
              badge: 'Posisi wajah tidak pas',
              hint: 'Hadapkan wajah lurus ke kamera dan usahakan jangan miring.',
              message: 'Posisi wajah belum sesuai. Hadapkan wajah lurus ke kamera dan usahakan jangan miring.'
            },
            {
              hasil: 'cahaya_kurang',
              posisi: ['Pas', 'good'],
              jarak: ['Ideal', 'good'],
              cahaya: ['Kurang', 'bad'],
              status: ['Perlu penyesuaian', 'warn'],
              badge: 'Pencahayaan kurang',
              hint: 'Pindah ke area yang lebih terang agar wajah bisa terbaca.',
              message: 'Pencahayaan kurang terang. Silakan pindah ke tempat yang lebih terang agar wajah bisa terbaca.'
            }
          ];

          const result = kondisi[Math.floor(Math.random() * kondisi.length)];

          setIndicator(indikatorPosisi, result.posisi[0], result.posisi[1]);
          setIndicator(indikatorJarak, result.jarak[0], result.jarak[1]);
          setIndicator(indikatorCahaya, result.cahaya[0], result.cahaya[1]);
          setIndicator(indikatorStatus, result.status[0], result.status[1]);

          scanBadge.innerHTML = '<span class="scan-dot"></span> ' + result.badge;
          scanHintBox.innerHTML = result.hint;

          if (result.hasil === 'berhasil') {
            scanResult.className = 'scan-result-box scan-result-success show';
            scanResult.innerHTML = result.message;
            btnTandaiHadir.disabled = false;
          } else {
            scanResult.className = 'scan-result-box scan-result-fail show';
            scanResult.innerHTML = result.message;
            btnTandaiHadir.disabled = true;
          }
        }, 2500);
      });
    }
  });
</script>
@endsection