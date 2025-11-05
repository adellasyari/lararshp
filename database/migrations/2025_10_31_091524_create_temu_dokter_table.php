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
        Schema::create('temu_dokter', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idpet');
            $table->unsignedBigInteger('idpemilik');
            $table->date('tanggal');
            $table->time('waktu');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('idpet')->references('idpet')->on('pet');
            $table->foreign('idpemilik')->references('idpemilik')->on('pemilik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temu_dokter');
    }
};
