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
        Schema::create('setting_admin', function (Blueprint $table) { 
            $table->id();
            $table->string('nama_expo', 255); 
            $table->string('logo_expo'); 
            $table->text('tentang');
            $table->text('alamat')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::create('sosmed', function (Blueprint $table) {
            $table->id();
            $table->string('facebook');
            $table->string('instagram'); 
            $table->string('youtube');
            $table->timestamps();
        });

        Schema::create('recaptcha_configs', function (Blueprint $table) {
            $table->id();
            $table->string('RECAPTCHA_SITE_KEY')->nullable();
            $table->string('RECAPTCHA_SECRET_KEY')->nullable();
            $table->timestamps();
        });

        Schema::create('google_configs', function (Blueprint $table) {
            $table->id();
            $table->string('GOOGLE_CLIENT_ID')->nullable();
            $table->text('GOOGLE_CLIENT_SECRET')->nullable();
            $table->string('GOOGLE_REDIRECT_URI')->nullable();
            $table->string('GOOGLE_CONNECT_URL')->nullable();
            $table->timestamps();
        });

        Schema::create('mail_configs', function (Blueprint $table) {
            $table->id();
            $table->string('MAIL_MAILER')->default('smtp');
            $table->string('MAIL_HOST')->nullable();
            $table->string('MAIL_PORT')->default('587');
            $table->string('MAIL_USERNAME')->nullable();
            $table->string('MAIL_PASSWORD')->nullable();
            $table->string('MAIL_ENCRYPTION')->nullable();
            $table->string('MAIL_FROM_ADDRESS')->nullable();
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_admin'); 
        Schema::dropIfExists('sosmed');
        Schema::dropIfExists('recaptcha_configs');
        Schema::dropIfExists('google_configs');
        Schema::dropIfExists('mail_configs');
    }
};