@extends('layout.master')

@section('title', 'Data Siswa')

@section('content')
<style>
  .student-page {
    margin: 0 !important;
    padding: 0 !important;
  }

  .student-card {
    border-radius: 20px;
    border: 1px solid #edf0f5;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
    margin: 0 !important;
    background: #fff;
  }

  .student-card-body {
    padding: 24px !important;
  }

  .student-title {
    font-size: 22px;
    font-weight: 700;
    color: #25324b;
    margin-bottom: 4px;
  }

  .student-subtitle {
    font-size: 14px;
    color: #8a94a6;
  }

  .student-total-box {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #eef4ff;
    color: #3b82f6;
    border-radius: 12px;
    padding: 10px 16px;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
  }

  .top-filter-row {
    display: grid;
    grid-template-columns: 1.3fr 1fr 1fr 1fr auto auto;
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

  .filter-input::placeholder {
    color: #9aa4b2;
  }

  .btn-download,
  .btn-add-data {
    border-radius: 12px;
    min-height: 46px;
    padding: 10px 18px;
    font-weight: 600;
    white-space: nowrap;
  }

  .btn-download {
    border: 1px solid #dbe3ef;
    background: #fff;
    color: #44546a;
  }

  .btn-download:hover {
    background: #f7f9fc;
    color: #25324b;
  }

  .table-wrapper {
    margin-top: 18px;
    max-height: 520px;
    overflow-y: auto;
    overflow-x: auto;
    border-radius: 16px;
  }

  .table-wrapper::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  .table-wrapper::-webkit-scrollbar-thumb {
    background: #cfd6e4;
    border-radius: 10px;
  }

  .custom-table {
    min-width: 1180px;
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

  .custom-table tbody tr:nth-child(odd) {
    background-color: #f9fbff;
  }

  .custom-table tbody tr:nth-child(even) {
    background-color: #ffffff;
  }

  .custom-table tbody tr:hover {
    background-color: #eef4ff;
  }

  td:last-child {
    white-space: nowrap;
  }

  .status-badge {
    display: inline-block;
    padding: 7px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
  }

  .status-aktif {
    background: #eaf8ef;
    color: #1f9d57;
  }

  .status-tidak-aktif {
    background: #fdecec;
    color: #dc3545;
  }

  .btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    padding: 7px 14px;
    font-size: 14px;
    font-weight: 500;
  }

  .table-footer-box {
    border-top: 1px solid #edf0f5;
    padding-top: 16px;
    margin-top: 16px;
  }

  .pagination {
    margin-bottom: 0;
  }

  .pagination .page-link {
    border: none;
    color: #495057;
    border-radius: 10px;
    margin: 0 3px;
    min-width: 38px;
    text-align: center;
    box-shadow: none !important;
  }

  .pagination .page-item.active .page-link {
    background-color: #eef3ff;
    color: #0d6efd;
    font-weight: 700;
  }

  @media (max-width: 1200px) {
    .top-filter-row {
      grid-template-columns: 1fr 1fr 1fr;
    }
  }

  @media (max-width: 768px) {
    .top-filter-row {
      grid-template-columns: 1fr;
    }
  }

  .table-footer-box .pagination {
  margin-bottom: 0;
}

.table-footer-box .page-link {
  border: none;
  color: #495057;
  border-radius: 10px;
  margin: 0 3px;
  min-width: 38px;
  text-align: center;
  box-shadow: none !important;
  background: transparent;
}

.table-footer-box .page-item.active .page-link {
  background-color: #eef3ff;
  color: #0d6efd;
  font-weight: 700;
}

.table-footer-box .page-item.disabled .page-link {
  color: #adb5bd;
  background: transparent;
}
</style>

<div class="student-page">
  <div class="card student-card">
    <div class="card-body student-card-body">

      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
          <div class="student-title">Data Siswa</div>
          <div class="student-subtitle">Manajemen data siswa</div>
        </div>

        <div class="student-total-box">
          Total Siswa: {{ $total_siswa }}
        </div>
      </div>

      <form action="{{ route('siswa.index') }}" method="GET" id="filterForm">
        <div class="top-filter-row">
          <input
            type="text"
            name="search"
            class="form-control filter-input"
            placeholder="Cari Siswa"
            value="{{ request('search') }}"
            id="searchInput">

          <select name="jenis_kelamin" class="form-select filter-select auto-submit">
            <option value="">Semua Jenis Kelamin</option>
            <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
            <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
          </select>

          <select name="kelas_id" class="form-select filter-select auto-submit">
            <option value="">Semua Kelas</option>
            @foreach($list_kelas as $kelas)
              <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                {{ $kelas->nama_kelas }}
              </option>
            @endforeach
          </select>

          <select name="status" class="form-select filter-select auto-submit">
            <option value="">Semua Status</option>
            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
          </select>

          <a href="{{ route('siswa.export') }}" class="btn btn-light">
              Download Excel
          </a>

          <a href="{{ url('/admin/siswa/create') }}" class="btn btn-primary btn-add-data">
            Tambah Data Siswa
          </a>
        </div>
      </form>

      <div class="table-wrapper">
        <table class="table custom-table align-middle">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Siswa</th>
              <th>Jenis Kelamin</th>
              <th>Tempat, Tanggal Lahir</th>
              <th>Kelas</th>
              <th>Status</th>
              <th style="min-width: 190px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($data_siswa as $no => $siswa)
            <tr>
              <td>{{ $data_siswa->firstItem() + $no }}</td>
              <td>{{ $siswa->nama_siswa }}</td>
              <td>{{ $siswa->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
              <td>{{ $siswa->ttl }}</td>
              <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
              <td>
                <span class="status-badge {{ $siswa->status == 'Aktif' ? 'status-aktif' : 'status-tidak-aktif' }}">
                  {{ $siswa->status }}
                </span>
              </td>
              <td>
                <div class="d-flex gap-2">
                  <a href="{{ route('siswa.edit', $siswa->id) }}" class="btn btn-warning text-white btn-action">
                    Edit
                  </a>

                  <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin mau hapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-action">
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="7" class="text-center text-muted py-4">
                Data siswa belum ada.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="table-footer-box d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="text-muted small">
          Menampilkan {{ $data_siswa->firstItem() ?? 0 }} - {{ $data_siswa->lastItem() ?? 0 }} dari {{ $data_siswa->total() }} data
        </div>

        <div>
           {{ $data_siswa->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');
    const selects = document.querySelectorAll('.auto-submit');
    const searchInput = document.getElementById('searchInput');

    selects.forEach(select => {
      select.addEventListener('change', function () {
        form.submit();
      });
    });

    searchInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        form.submit();
      }
    });
  });
</script>
@endsection