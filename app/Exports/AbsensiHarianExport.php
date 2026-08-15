<?php

namespace App\Exports;

use App\Models\AbsensiHarian;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiHarianExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = AbsensiHarian::with([
            'siswa.kelas',
        ]);

        if (!empty($this->filters['kelas_id'])) {
            $query->whereHas('siswa', function ($q) {
                $q->where('kelas_id', $this->filters['kelas_id']);
            });
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

                return [

                    'Tanggal' => $item->tanggal
                        ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')
                        : '-',

                    'Siswa' => $item->siswa?->nama_siswa ?? '-',

                    'Kelas' => $item->siswa?->kelas?->nama_kelas ?? '-',

                    'Status' => $item->status ?? '-',

                    'Terlambat' => $item->terlambat ? 'Ya' : 'Tidak',

                    'Catatan' => $item->keterangan ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Siswa',
            'Kelas',
            'Status',
            'Terlambat',
            'Catatan',
        ];
    }
}