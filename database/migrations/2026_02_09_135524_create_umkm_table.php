<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('umkm', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Relasi
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null')
                ->unique();
            
            $table->foreignId('unit_id')
                ->constrained('units')
                ->onDelete('cascade');

            $table->foreignId('kategori_id')
                ->nullable()
                ->constrained('kategori')
                ->onDelete('set null');
            
            // ===== DATA USAHA =====
            $table->string('nama_pemilik');
            $table->string('nama_usaha');
            $table->year('tahun_berdiri')->nullable();
            $table->string('telepon');
            $table->string('email')->unique();
            $table->text('alamat');
            
            // ===== LOKASI WILAYAH INDONESIA (Laravolt) =====
            $table->char('province_code', 2)->nullable();
            $table->char('city_code', 4)->nullable();
            $table->char('district_code', 7)->nullable();
            $table->char('village_code', 10)->nullable();
            $table->string('kode_pos', 5)->nullable();
            
            // Foreign keys ke tabel Laravolt Indonesia
            $table->foreign('province_code')
                ->references('code')
                ->on(config('laravolt.indonesia.table_prefix', '').'provinces')
                ->onUpdate('cascade')
                ->onDelete('set null');
                
            $table->foreign('city_code')
                ->references('code')
                ->on(config('laravolt.indonesia.table_prefix', '').'cities')
                ->onUpdate('cascade')
                ->onDelete('set null');
                
            $table->foreign('district_code')
                ->references('code')
                ->on(config('laravolt.indonesia.table_prefix', '').'districts')
                ->onUpdate('cascade')
                ->onDelete('set null');
                
            $table->foreign('village_code')
                ->references('code')
                ->on(config('laravolt.indonesia.table_prefix', '').'villages')
                ->onUpdate('cascade')
                ->onDelete('set null');
            
            // ===== BRANDING (UMKM yang isi setelah login) =====
            $table->string('logo_umkm')->nullable();
            $table->string('qris_foto')->nullable();
            $table->text('tentang')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable(); 
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            
            // ===== ADMIN & STATUS =====
            $table->string('kode_umkm')->unique();
            $table->date('tanggal_bergabung');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            
            // Audit trail - SEMUA NULLABLE
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            
            // IMPORTANT: Set default values for timestamps
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Indexes
            $table->index('uuid');
            $table->index('user_id');
            $table->index('unit_id');
            $table->index('kategori_id');
            $table->index('kode_umkm');
            $table->index('status');
            $table->index('email');
            $table->index('nama_usaha');
            $table->index('created_at');
            $table->index('province_code');
            $table->index('city_code');
            $table->index('district_code');
            $table->index('village_code');
        });

        Schema::create('whatsapp_configs', function (Blueprint $table) {
            $table->id();
            $table->string('api_key')->nullable();
            $table->string('api_url')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm');
        Schema::dropIfExists('whatsapp_configs');
    }
};