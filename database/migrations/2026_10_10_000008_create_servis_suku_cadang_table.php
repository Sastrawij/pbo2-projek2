<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servis_suku_cadang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->constrained('servis')->cascadeOnDelete();
            $table->foreignId('suku_cadang_id')->constrained('suku_cadang')->restrictOnDelete();
            $table->unsignedInteger('jumlah')->default(1);
            $table->unsignedBigInteger('harga')->default(0); // harga satuan saat transaksi
            $table->timestamps();

            $table->unique(['servis_id', 'suku_cadang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis_suku_cadang');
    }
};
