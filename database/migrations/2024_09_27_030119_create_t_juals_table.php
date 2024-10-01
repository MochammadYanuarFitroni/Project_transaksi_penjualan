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
        Schema::create('t_juals', function (Blueprint $table) {
            // $table->id();
            $table->char('no_faktur', 6);
            $table->primary('no_faktur');
            $table->char('kode_customer', 4);
            $table->char('kode_tjen', 1);
            $table->date('tgl_faktur');
            $table->decimal('total_bruto', 15, 2);
            $table->decimal('total_diskon', 15, 2);
            $table->decimal('total_jumlah', 15, 2);
            $table->timestamps();

            $table->foreign('kode_customer')->references('kode_customer')->on('customers')->onDelete('cascade');
            $table->foreign('kode_tjen')->references('kode_tjen')->on('tjenis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_juals');
    }
};
