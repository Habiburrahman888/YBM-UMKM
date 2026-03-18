<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleConfig extends Model
{
    use HasFactory;

    protected $table = 'google_configs';

    protected $fillable = [
        'GOOGLE_CLIENT_ID',
        'GOOGLE_CLIENT_SECRET',
        'GOOGLE_REDIRECT_URI',
        'GOOGLE_CONNECT_URL',
    ];
}
