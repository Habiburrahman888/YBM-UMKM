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
        Schema::create('produk_umkm', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relasi ke UMKM (TANPA unique - one to many)
            $table->foreignId('umkm_id')
                ->constrained('umkm')
                ->onDelete('cascade');

            $table->string('nama_produk');
            $table->text('deskripsi_produk');
            $table->json('foto_produk')->nullable();
            $table->decimal('harga', 15, 2);
            $table->integer('stok')->nullable();
            $table->enum('kategori_satuan', [
                'pcs', 
                'bungkus', 
                'gram', 
                'kg', 
                'liter', 
                'ml', 
                'box', 
                'pack'
            ])->nullable();

            // created_by = siapa yg pertama input (unit atau umkm)
            // updated_by = siapa yg terakhir edit (unit atau umkm)
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['uuid', 'umkm_id', 'harga']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_umkm');
    }
};