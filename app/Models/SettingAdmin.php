<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettingAdmin extends Model
{
    use HasFactory;

    protected $table = 'setting_admin';

    protected $fillable = [
        'nama_expo',
        'logo_expo',
        'tentang',
        'alamat',
        'email',
        'phone',
        'sosmed_id',
    ];

    public function sosmed(): BelongsTo
    {
        return $this->belongsTo(Sosmed::class, 'sosmed_id');
    }

    public function getLogoExpoUrlAttribute(): ?string
    {
        if ($this->logo_expo) {
            return asset('storage/' . $this->logo_expo);
        }
        return null;
    }

    public function hasSosmed(): bool
    {
        return !is_null($this->sosmed_id);
    }
}
