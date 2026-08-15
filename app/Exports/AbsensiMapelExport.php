<?php

namespace App\Exports;

use App\Models\AbsensiMapel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiMapelExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = AbsensiMapel::with([
            'siswa',
            'jadwalPelajaran.mataPelajaran',
            'jadwalPelajaran.kelas',
        ]);

        if (!empty($this->filters['kelas_id'])) {
            $query->whereHas(
                'jadwalPelajaran.kelas',
                fn ($q) => $q->where(
                    'id',
                    $this->filters['kelas_id']
                )
            );
        }

        if (!empty($this->filters['mata_pelajaran_id'])) {
            $query->whereHas(
                'jadwalPelajaran.mataPelajaran',
                fn ($q) => $q->where(
                    'id',
                    $this->filters['mata_pelajaran_id']
                )
            );
        }

        if (!empty($this->filters['tanggal_dari'])) {
            $query->whereDate(
                'tanggal',
                '>=',
                $this->filters['tanggal_dari']
            );
        }

        if (!empty($this->filters['tanggal_sampai'])) {
            $query->whereDate(
                'tanggal',
                '<=',
                $this->filters['tanggal_sampai']
            );
        }

        if (!empty($this->filters['status'])) {
            $query->where(
                'status',
                $this->filters['status']
            );
        }

        if (!empty($this->filters['siswa_id'])) {
            $query->where(
                'siswa_id',
                $this->filters['siswa_id']
            );
        }

        return $query
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($item) {
                $jadwal = $item->jadwalPelajaran;

                $jamMulai = $jadwal && $jadwal->jam_mulai
                    ? \Carbon\Carbon::parse(
                        $jadwal->jam_mulai
                    )->format('H:i')
                    : '-';

                $jamSelesai = $jadwal && $jadwal->jam_selesai
                    ? \Carbon\Carbon::parse(
                        $jadwal->jam_selesai
                    )->format('H:i')
                    : '-';

                return [
                    'Tanggal' => $item->tanggal
                        ? \Carbon\Carbon::parse(
                            $item->tanggal
                        )->format('d-m-Y')
                        : '-',

                    'Siswa' =>
                        $item->siswa?->nama_siswa ?? '-',

                    'Kelas' =>
                        $jadwal?->kelas?->nama_kelas ?? '-',

                    'Jam' =>
                        $jamMulai . ' - ' . $jamSelesai,

                    'Mata Pelajaran' =>
                        $jadwal?->mataPelajaran?->nama_mapel ?? '-',

                    'Status' =>
                        $item->status ?? '-',

                    'Catatan Guru (Opsional)' =>
                        $item->catatan ?? '-',

                    'Nilai Disiplin' =>
                        $item->scan_score ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Siswa',
            'Kelas',
            'Jam',
            'Mata Pelajaran',
            'Status',
            'Catatan Guru (Opsional)',
            'Nilai Disiplin',
        ];
    }
}