<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('umkm_id')->constrained('umkm')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk_umkm')->onDelete('cascade');
            $table->string('nama_pembeli');
            $table->string('telepon_pembeli');
            $table->text('alamat_pembeli');
            $table->integer('jumlah');
            $table->decimal('total_harga', 15, 2);
            $table->string('bukti_transfer')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->enum('status', ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
