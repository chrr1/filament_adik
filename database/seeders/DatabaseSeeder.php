<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Factories\ProdukFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'user_group_id' => 1,
            'password' => Hash::make('123456'),
        ]);
        Produk::factory()->count(10)->create();
    }
}
