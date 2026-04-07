<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sosmed extends Model
{
    use HasFactory;

    protected $table = 'sosmed';

    protected $fillable = [
        'facebook',
        'instagram',
        'youtube',
    ];

    public function getFacebookAttribute($value)
    {
        if (!$value) return null;
        return (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) ? $value : 'https://' . $value;
    }

    public function getInstagramAttribute($value)
    {
        if (!$value) return null;
        return (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) ? $value : 'https://' . $value;
    }

    public function getYoutubeAttribute($value)
    {
        if (!$value) return null;
        return (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) ? $value : 'https://' . $value;
    }
}