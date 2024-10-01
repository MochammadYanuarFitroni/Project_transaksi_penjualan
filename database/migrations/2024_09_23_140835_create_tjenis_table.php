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
        Schema::create('tjenis', function (Blueprint $table) {
            // $table->id();
            $table->char('kode_tjen',1);
            $table->primary('kode_tjen');
            $table->string('nama_tjen', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tjenis');
    }
};
