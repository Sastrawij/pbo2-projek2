<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servis_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->constrained('servis')->cascadeOnDelete();
            $table->foreignId('layanan_id')->constrained('layanan')->restrictOnDelete();
            $table->unsignedBigInteger('harga')->default(0); // harga saat transaksi
            $table->timestamps();

            $table->unique(['servis_id', 'layanan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis_layanan');
    }
};
