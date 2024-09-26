<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penjualan extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'pelanggan_id',
        'produk_id',
        'quantity',
        'harga_satuan',
        'total_harga',
        'tanggal_penjualan',
        'created_by',
    'updated_by',
    'deleted_by',
        
    ];

    // Cast tanggal_penjualan sebagai datetime
    protected $casts = [
        'tanggal_penjualan' => 'datetime',
    ];

    // Relasi dengan model Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // Relasi dengan model Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
