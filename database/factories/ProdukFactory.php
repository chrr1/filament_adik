<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition()
    {
        // Daftar nama produk dalam bahasa Indonesia
        $namaProduks = [
            'Kopi Arabika',
            'Teh Hijau',
            'Minyak Kelapa',
            'Sabun Cuci Piring',
            'Shampoo Herbal',
            'Kacang Almond',
            'Cokelat Hitam',
            'Beras Merah',
            'Minyak Zaitun',
            'Madu Murni'
        ];

        return [
            'NamaProduk' => $this->faker->randomElement($namaProduks),
            'Harga' => $this->faker->randomFloat(2, 10000, 500000), // Harga antara 10.000 - 500.000
            'Stok' => $this->faker->numberBetween(10, 100), // Stok antara 10 - 100
            'branch_id' => $this->faker->numberBetween(1, 2), // Stok antara 10 - 100
        ];
    }
}
