<?php 
namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenjualanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil penjualan beserta relasinya
        return Penjualan::with(['pelanggan', 'produk'])->get()->map(function($penjualan) {
            return [
                'id' => $penjualan->id,
                'NamaPelanggan' => $penjualan->pelanggan ? $penjualan->pelanggan->NamaPelanggan : '',
                'NamaProduk' => $penjualan->produk ? $penjualan->produk->NamaProduk : '',
                'Jumlah' => $penjualan->quantity,
                'HargaSatuan' => $penjualan->harga_satuan,
                'TotalHarga' => $penjualan->total_harga,
                'TanggalPenjualan' => $penjualan->tanggal_penjualan->format('Y-m-d'),
            ];
        });
    }

    // Menambahkan header kolom di Excel
    public function headings(): array
    {
        return [
            'ID',
            'Nama Pelanggan',
            'Nama Produk',
            'Jumlah',
            'Harga Satuan',
            'Total Harga',
            'Tanggal Penjualan',
        ];
    }
}
