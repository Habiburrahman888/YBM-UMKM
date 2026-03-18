<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecaptchaConfig extends Model
{
    use HasFactory;

    protected $table = 'recaptcha_configs';

    protected $fillable = [
        'RECAPTCHA_SITE_KEY',
        'RECAPTCHA_SECRET_KEY',
    ];
}
