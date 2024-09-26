<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PelangganFactory extends Factory
{
    protected $model = Pelanggan::class;

    public function definition()
    {
        // Daftar nama pelanggan dalam bahasa Indonesia
        $namaPelanggans = [
            'Andi Setiawan',
            'Budi Santoso',
            'Citra Dewi',
            'Dewi Lestari',
            'Eko Prabowo',
            'Fajar Hidayat',
            'Gita Nurul',
            'Hendra Gunawan',
            'Ika Amelia',
            'Joko Widodo'
        ];

        return [
            'NamaPelanggan' => $this->faker->randomElement($namaPelanggans),
            'Alamat' => $this->faker->address,
            'NomorTelepon' => $this->faker->phoneNumber,
            // Tambahkan kolom lain sesuai kebutuhan, misalnya:
            // 'created_by' => '1', // Contoh jika ada kolom created_by
        ];
    }
}
