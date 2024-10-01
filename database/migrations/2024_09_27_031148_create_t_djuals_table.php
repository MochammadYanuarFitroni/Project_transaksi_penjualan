<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_djuals', function (Blueprint $table) {
            $table->id();
            $table->char('no_faktur', 6);
            // $table->primary('no_faktur');
            $table->char('kode_barang', 10);
            $table->decimal('harga', 15, 2);
            $table->decimal('qty', 15, 2);
            $table->decimal('diskon', 15, 2);
            $table->decimal('bruto', 15, 2);
            $table->decimal('jumlah', 15, 2);
            $table->timestamps();

            $table->foreign('kode_barang')->references('kode_barang')->on('barangs')->onDelete('cascade');
            $table->foreign('no_faktur')->references('no_faktur')->on('t_juals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_djuals');
    }
};
