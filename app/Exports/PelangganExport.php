<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PelangganExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Mengambil semua data pelanggan
        return Pelanggan::all();
    }

    public function headings(): array
    {
        // Mendefinisikan header kolom di file Excel
        return [
            'ID',
            'Nama Pelanggan',
            'Alamat',
            'Nomor Telepon',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }
}
