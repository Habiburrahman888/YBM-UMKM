<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('units', function (Blueprint $table) {
            // Cek apakah kolom user_id sudah ada
            if (!Schema::hasColumn('units', 'user_id')) {
                $table->foreignId('user_id')
                      ->after('id')
                      ->constrained('users')
                      ->onDelete('cascade');
                
                $table->index('user_id', 'idx_units_user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('units', function (Blueprint $table) {
            if (Schema::hasColumn('units', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropIndex('idx_units_user_id');
                $table->dropColumn('user_id');
            }
        });
    }
};