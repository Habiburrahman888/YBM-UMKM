<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name', 50)->default('default')->index();

            // Siapa yang melakukan
            $table->unsignedBigInteger('causer_id')->nullable()->index();
            $table->string('causer_type', 100)->nullable(); // e.g. App\Models\Users
            $table->string('causer_name', 100)->nullable();  // snapshot nama user saat itu
            $table->string('causer_role', 30)->nullable();   // snapshot role

            // Apa yang dilakukan
            $table->string('event', 50)->index();            // create | update | delete | login | logout | approve | reject | verify | ...
            $table->string('description')->nullable();        // deskripsi human-readable

            // Objek yang terkena aksi
            $table->string('subject_type', 100)->nullable(); // e.g. App\Models\Umkm
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('subject_label')->nullable();     // snapshot nama / label subject

            // Perubahan (before/after)
            $table->json('properties')->nullable();          // ['old' => [...], 'new' => [...]]

            // Request metadata
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 300)->nullable();

            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
