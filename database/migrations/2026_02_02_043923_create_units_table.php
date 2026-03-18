<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('user_id')->nullable()
            ->constrained('users')
            ->onDelete('set null');

            // 1. DATA ADMIN UNIT (PALING ATAS)
            $table->string('admin_nama')->nullable();
            $table->string('admin_telepon')->nullable();
            $table->string('admin_email')->nullable();
            $table->string('admin_foto')->nullable();
            
            // 2. Data Unit
            $table->string('nama_unit')->unique();
            $table->string('kode_unit')->unique();
            $table->string('logo')->nullable();

            // Kode wilayah (char)
            $table->char('provinsi_kode', 2)->nullable();
            $table->char('kota_kode', 4)->nullable();
            $table->char('kecamatan_kode', 7)->nullable();
            $table->char('kelurahan_kode', 10)->nullable();

            // Nama wilayah (denormalized)
            $table->string('provinsi_nama')->nullable();
            $table->string('kota_nama')->nullable();
            $table->string('kecamatan_nama')->nullable();
            $table->string('kelurahan_nama')->nullable();

            // Kontak & lokasi
            $table->string('kode_pos', 5)->nullable();
            $table->string('telepon')->nullable();
            $table->string('unit_email')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('alamat');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('provinsi_kode')
                ->references('code')
                ->on(config('laravolt.indonesia.table_prefix', '').'provinces')
                ->onDelete('set null');

            $table->foreign('kota_kode')
                ->references('code')
                ->on(config('laravolt.indonesia.table_prefix', '').'cities')
                ->onDelete('set null');

            $table->foreign('kecamatan_kode')
                ->references('code')
                ->on(config('laravolt.indonesia.table_prefix', '').'districts')
                ->onDelete('set null');

            $table->foreign('kelurahan_kode')
                ->references('code')
                ->on(config('laravolt.indonesia.table_prefix', '').'villages')
                ->onDelete('set null');
            
            // Indexes
            $table->index('kode_unit', 'idx_units_kode');
            $table->index('is_active', 'idx_units_is_active');
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['provinsi_kode']);
            $table->dropForeign(['kota_kode']);
            $table->dropForeign(['kecamatan_kode']);
            $table->dropForeign(['kelurahan_kode']);
        });
        Schema::dropIfExists('units');
    }
};