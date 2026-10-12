<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            $table->string('no_servis', 30)->unique();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->restrictOnDelete();
            $table->foreignId('mekanik_id')->nullable()->constrained('mekanik')->nullOnDelete();
            $table->date('tanggal');
            $table->text('keluhan');
            $table->enum('status', ['menunggu', 'dikerjakan', 'selesai', 'diambil'])->default('menunggu');
            $table->unsignedBigInteger('total')->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};
