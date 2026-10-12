<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->restrictOnDelete();
            $table->string('no_polisi', 15)->unique();
            $table->enum('jenis', ['Motor', 'Mobil'])->default('Motor');
            $table->string('merk', 50);
            $table->string('tipe', 50);
            $table->unsignedSmallInteger('tahun')->nullable();
            $table->string('warna', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
