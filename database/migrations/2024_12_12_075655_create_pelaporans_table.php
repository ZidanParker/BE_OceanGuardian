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
        Schema::create('pelaporan', function (Blueprint $table) {
            $table->id('id_pelaporan'); // ID Auto Increment
            $table->string('nama'); // Nama pelapor
            $table->string('provinsi'); // Nama provinsi
            $table->text('lokasi'); // Lokasi kejadian
            $table->enum('jenis_laporan', ['Pembuangan Sampah Laut', 'Penangkapan Ikan Illegal']); // Jenis laporan
            $table->enum('status', ['Belum Diproses', 'Sedang Diproses', 'Selesai Diproses']); // Status laporan
            $table->string('gambar')->nullable(); // Path gambar (nullable)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelaporans');
    }
};
