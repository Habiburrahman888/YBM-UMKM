<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // DB::statement is used because Laravel Blueprint doesn't support modifying ENUM directly
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status ENUM('pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE pesanan MODIFY COLUMN status ENUM('pending', 'diproses', 'selesai', 'dibatalkan') DEFAULT 'pending'");
    }
};
