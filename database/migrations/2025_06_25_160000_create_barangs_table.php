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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 30)->unique();
            $table->string('nama_barang', 100);
            $table->string('gambar', 150);
            $table->integer('stok_minimum');
            $table->integer('stok_maksimum');
            $table->integer('stok')->nullable()->default(0);
            $table->foreignId('jenis_id')->constrained('jenis');
            $table->foreignId('satuan_id')->constrained('satuans');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
