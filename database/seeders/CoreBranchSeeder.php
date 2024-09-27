<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CoreBranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('core_branches')->insert([[
            "branch_name" => 'Branch Aseli',
            "branch_address" => 'Alamat Branch Aseli'
        ],
        [
            "branch_name" => 'Branch Kedua',
            "branch_address" => 'Alamat Branch Kedua'
        ],
    ]);
    }
}
