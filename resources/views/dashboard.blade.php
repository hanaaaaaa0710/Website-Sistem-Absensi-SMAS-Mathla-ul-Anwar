@extends('layout.master')

@section('title', 'Dashboard Kehadiran')

@section('content')
<style>
  .dashboard-card {
    border: 1px solid #edf0f5;
    border-radius: 18px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    background: #fff;
  }

  .stat-card {
    border: 1px solid #edf0f5;
    border-radius: 16px;
    background: #fff;
    height: 100%;
  }

  .mini-avatar {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    object-fit: cover;
  }

  .status-badge {
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
  }

  .status-hadir {
    background: #eaf8ef;
    color: #1f9d57;
  }

  .status-izin,
  .status-sakit {
    background: #fff4db;
    color: #d4a017;
  }

  .status-alpha {
    background: #fdecec;
    color: #dc3545;
  }

  .notif-box {
    border-radius: 14px;
    padding: 16px;
    height: 100%;
  }

  .notif-success {
    background: #eaf8ef;
  }

  .notif-danger {
    background: #fdecec;
  }

  .notif-warning {
    background: #fff8e7;
  }

  .chart-box {
    min-height: 450px;
    padding-bottom: 40px;
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
</style>

<div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
  <div>
    <h3 class="fw-bold mb-1">Dashboard Kehadiran</h3>
    <p class="text-muted mb-0">Monitoring real-time kehadiran siswa</p>
  </div>

  <div class="dashboard-card px-3 py-2">
    <small class="text-muted d-block">Hari ini</small>
    <span class="fw-semibold text-primary">
      {{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}
    </span>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-6 col-xl-3">
    <div class="stat-card p-3">
      <p class="text-muted mb-1">Total Siswa</p>
      <h2 class="fw-bold text-primary mb-1">{{ $totalSiswa }}</h2>
      <small class="text-muted">100% dari total siswa</small>
    </div>
  </div>

  <div class="col-md-6 col-xl-3">
    <div class="stat-card p-3">
      <p class="text-muted mb-1">Total Hadir</p>
      <h2 class="fw-bold text-success mb-1">{{ $totalHadir }}</h2>
      <small class="text-muted">{{ $persenHadir }}% dari total siswa</small>
    </div>
  </div>

  <div class="col-md-6 col-xl-3">
    <div class="stat-card p-3">
      <p class="text-muted mb-1">Izin / Sakit</p>
      <h2 class="fw-bold text-warning mb-1">{{ $totalIzinSakit }}</h2>
      <small class="text-muted">{{ $persenIzinSakit }}% dari total siswa</small>
    </div>
  </div>

  <div class="col-md-6 col-xl-3">
    <div class="stat-card p-3">
      <p class="text-muted mb-1">Alpha</p>
      <h2 class="fw-bold text-danger mb-1">{{ $totalAlpha }}</h2>
      <small class="text-muted">{{ $persenAlpha }}% dari total siswa</small>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-4">
    <div class="card dashboard-card h-100">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Grafik Status Kehadiran</h5>
        <div id="chartStatus" class="chart-box"></div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card dashboard-card h-100">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Grafik Notifikasi</h5>
        <div id="chartNotif" class="chart-box"></div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card dashboard-card h-100">
      <div class="card-body">
        <h5 class="fw-bold mb-3">Hadir per Kelas</h5>
        <div id="chartKelas" class="chart-box"></div>
      </div>
    </div>
  </div>
</div>

<div class="card dashboard-card mb-4">
  <div class="card-body">
    <h5 class="fw-bold mb-4">Absensi Terbaru</h5>

    <div class="row g-3">
      @forelse($absensiTerbaru as $item)
      <div class="col-md-6 col-xl-4">
        <div class="border rounded-4 p-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-3">
            <img src="{{ asset('admin/assets/images/profile/user-1.jpg') }}" class="mini-avatar" alt="">
            <div>
              <div class="fw-semibold">{{ $item->siswa->nama_siswa ?? '-' }}</div>
              <small class="text-muted">{{ $item->siswa->kelas ?? '-' }}</small>
            </div>
          </div>

          <div class="text-end">
            <div class="fw-semibold">
              {{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}
            </div>
            <span class="status-badge
              {{ $item->status == 'Hadir' ? 'status-hadir' : '' }}
              {{ in_array($item->status, ['Izin', 'Sakit']) ? 'status-izin' : '' }}
              {{ $item->status == 'Alpha' ? 'status-alpha' : '' }}">
              {{ $item->status }}
            </span>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12">
        <div class="text-muted">Belum ada data absensi hari ini.</div>
      </div>
      @endforelse
    </div>
  </div>
</div>

<div class="card dashboard-card">
  <div class="card-body">
    <h5 class="fw-bold mb-4">Notifikasi Terkirim</h5>

    <div class="row g-3">
      <div class="col-md-4">
        <div class="notif-box notif-success">
          <div class="fw-semibold">{{ $notifBerhasil }} Notifikasi Berhasil</div>
          <small class="text-muted">Orang tua telah menerima notifikasi kehadiran</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="notif-box notif-danger">
          <div class="fw-semibold">{{ $notifGagal }} Notifikasi Gagal Terkirim</div>
          <small class="text-muted">Orang tua gagal menerima notifikasi kehadiran</small>
        </div>
      </div>

      <div class="col-md-4">
        <div class="notif-box notif-warning">
          <div class="fw-semibold">{{ $notifMenunggu }} Menunggu Konfirmasi</div>
          <small class="text-muted">Status izin/sakit menunggu validasi</small>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const chartStatusEl = document.querySelector('#chartStatus');
    const chartNotifEl = document.querySelector('#chartNotif');
    const chartKelasEl = document.querySelector('#chartKelas');

    if (chartStatusEl) {
      const statusChart = new ApexCharts(chartStatusEl, {
        chart: {
          type: 'donut',
          height: 240,
          animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800
          }
        },
        series: [
          {{ $chartStatus['Hadir'] ?? 0 }},
          {{ $chartStatus['Izin'] ?? 0 }},
          {{ $chartStatus['Sakit'] ?? 0 }},
          {{ $chartStatus['Alpha'] ?? 0 }}
        ],
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
        colors: ['#22c55e', '#facc15', '#f97316', '#ef4444'],
        legend: {
          position: 'bottom',
          horizontalAlign: 'center',
          fontSize: '13px',
          offsetY: 0
        },
        plotOptions: {
  pie: {
    donut: {
      size: '65%',
      labels: {
        show: true,
        total: {
          show: true,
          label: 'Total Siswa',
          formatter: function () {
            return "{{ $totalSiswa }} Siswa";
          }
        }
      }
    }
  }
},
        dataLabels: {
          enabled: true
        }
      });
      statusChart.render();
    }

    if (chartNotifEl) {
      const notifChart = new ApexCharts(chartNotifEl, {
        chart: {
          type: 'pie',
          height: 240,
          animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800
          }
        },
        series: [
          {{ $chartNotif['Berhasil'] ?? 0 }},
          {{ $chartNotif['Gagal'] ?? 0 }},
          {{ $chartNotif['Menunggu'] ?? 0 }}
        ],
        labels: ['Berhasil', 'Gagal', 'Menunggu'],
        colors: ['#22c55e', '#ef4444', '#facc15'],
        legend: {
          position: 'bottom',
          horizontalAlign: 'center',
          fontSize: '13px',
          offsetY: 0
        },
        dataLabels: {
          enabled: true
        }
      });
      notifChart.render();
    }

    if (chartKelasEl) {
      const kelasChart = new ApexCharts(chartKelasEl, {
        chart: {
          type: 'bar',
          height: 300,
          toolbar: {
            show: false
          },
          animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800
          }
        },
        series: [{
          name: 'Jumlah Hadir',
          data: @json($kelasSeries ?? [])
        }],
        xaxis: {
          categories: @json($kelasLabels ?? [])
        },
        colors: ['#3b82f6'],
        dataLabels: {
          enabled: false
        }
      });
      kelasChart.render();
    }
  });
</script>
@endsection