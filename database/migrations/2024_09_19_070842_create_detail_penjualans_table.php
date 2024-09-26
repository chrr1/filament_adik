<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPenjualansTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualans')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produks')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Balikkan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_penjualans');
    }
}
