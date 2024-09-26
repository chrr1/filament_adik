<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenjualanFactory extends Factory
{
    protected $model = Penjualan::class;

    public function definition()
    {
        return [
            'pelanggan_id' => Pelanggan::factory(), // Menghubungkan dengan pelanggan
            'produk_id' => Produk::factory(),       // Menghubungkan dengan produk
            'quantity' => $this->faker->numberBetween(1, 100), // Jumlah produk
            'harga_satuan' => $this->faker->randomFloat(2, 1000, 10000), // Harga satuan
            'total_harga' => function (array $attributes) {
                return $attributes['quantity'] * $attributes['harga_satuan']; // Menghitung total harga
            },
            'tanggal_penjualan' => $this->faker->dateTimeThisYear(), // Tanggal penjualan
        ];
    }
}
