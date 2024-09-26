<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProdukExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Produk::all(); // Mengambil semua data produk
    }

    public function headings(): array
    {
        return [
            'ID', // Judul kolom untuk ID
            'Nama Produk', // Judul kolom untuk Nama Produk
            'Harga', // Judul kolom untuk Harga
            'Stok', // Judul kolom untuk Stok
            'Tanggal Dibuat', // Judul kolom untuk Tanggal Dibuat
        ];
    }

    public function map($produk): array
    {
        return [
            $produk->id, // ID produk
            $produk->NamaProduk, // Nama produk
            $produk->Harga, // Harga produk
            $produk->Stok, // Stok produk
            $produk->created_at, // Tanggal dibuat
        ];
    }
}
