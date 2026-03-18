<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('username')->unique()->nullable();
            $table->enum('role', ['admin', 'unit', 'umkm'])->default('unit');
            $table->string('email')->unique(); 
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('google_id')->nullable()->unique();
            $table->text('google_token')->nullable();  
            $table->text('refresh_token')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('foto_profil')->nullable();
            $table->string('verification_token')->nullable();
            $table->timestamp('verification_token_expires_at')->nullable();
            $table->string('password_reset_token')->nullable();
            $table->timestamp('password_reset_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Indexes
            $table->index('role', 'idx_users_role');
            $table->index('email_verified_at', 'idx_users_email_verified');
            $table->index('is_active', 'idx_users_is_active');
            $table->index('password_reset_token', 'idx_users_password_reset_token');
            $table->index('created_at', 'idx_users_created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};  