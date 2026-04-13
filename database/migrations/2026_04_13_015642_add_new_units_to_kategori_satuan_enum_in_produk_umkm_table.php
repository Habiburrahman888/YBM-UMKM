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
        Schema::table('produk_umkm', function (Blueprint $table) {
            $table->enum('kategori_satuan', [
                'pcs', 
                'bungkus', 
                'gram', 
                'kg', 
                'liter', 
                'ml', 
                'box', 
                'pack',
                'porsi',
                'cup',
                'karung',
                'paket',
                'unit'
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk_umkm', function (Blueprint $table) {
            $table->enum('kategori_satuan', [
                'pcs', 
                'bungkus', 
                'gram', 
                'kg', 
                'liter', 
                'ml', 
                'box', 
                'pack',
                'porsi'
            ])->nullable()->change();
        });
    }
};
