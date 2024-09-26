<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualansTable extends Migration
{
    public function up()
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained()->onDelete('cascade');
            $table->foreignId('produk_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->date('tanggal_penjualan');
            $table->foreignId('created_by')->nullable()->constrained('users'); // Kolom untuk created_by
            $table->foreignId('updated_by')->nullable()->constrained('users'); // Kolom untuk updated_by
            $table->foreignId('deleted_by')->nullable()->constrained('users'); // Kolom untuk deleted_by
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penjualans');
    }
}
