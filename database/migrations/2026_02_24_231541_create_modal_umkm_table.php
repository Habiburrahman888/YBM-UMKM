<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modal_umkm', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relasi ke UMKM
            $table->foreignId('umkm_id')
                ->constrained('umkm')
                ->onDelete('cascade');

            // ===== DATA ITEM MODAL =====
            $table->string('nama_item');
            // Contoh: Gerobak, Mesin Jahit, Etalase, Kompor Gas

            $table->enum('kategori_modal', [
                'peralatan',    // Kompor, blender, mesin jahit
                'kendaraan',    // Gerobak, motor, mobil
                'perlengkapan', // Etalase, rak, meja
                'bangunan',     // Kios, lapak, gudang
                'lainnya',
            ])->default('lainnya');

            $table->text('keterangan')->nullable();
            // Contoh: "Gerobak kayu ukuran 1x2m, kondisi baik"

            $table->unsignedBigInteger('nilai_modal');
            // Nilai dalam Rupiah, contoh: 5000000 = Rp 5.000.000

            $table->enum('kondisi', [
                'baru',
                'baik',
                'cukup',
                'rusak',
            ])->default('baik');

            $table->date('tanggal_perolehan')->nullable();

            // ===== FOTO (JSON) =====
            $table->json('foto')->nullable();

            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');

            // Audit trail
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Indexes
            $table->index('uuid');
            $table->index('umkm_id');
            $table->index('kategori_modal');
            $table->index('kondisi');
            $table->index('status');
            $table->index('nilai_modal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modal_umkm');
    }
};