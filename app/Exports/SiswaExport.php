<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Siswa::with(['kelas'])
            ->orderBy('nama_siswa')
            ->get()
            ->map(function ($siswa) {
                return [
                    'nama_siswa'    => $siswa->nama_siswa,
                    'jenis_kelamin' => $siswa->jenis_kelamin,
                    'ttl'           => $siswa->ttl,
                    'kelas'         => $siswa->kelas->nama_kelas ?? '-',
                    'nama_ortu'     => $siswa->nama_ortu ?? '-',
                    'no_hp_ortu'    => $siswa->no_hp_ortu ?? '-',
                    'status'        => $siswa->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Jenis Kelamin',
            'Tempat, Tanggal Lahir',
            'Kelas',
            'Nama Ortu',
            'No HP Ortu',
            'Status',
        ];
    }
}