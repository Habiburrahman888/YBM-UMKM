<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'username',
        'role',
        'email',
        'email_verified_at',
        'password',
        'google_id',
        'google_token',
        'refresh_token',
        'is_active',
        'foto_profil',
        'verification_token',
        'verification_token_expires_at',
        'password_reset_token',
        'password_reset_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_token',
        'refresh_token',
        'verification_token',
        'password_reset_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verification_token_expires_at' => 'datetime',
            'password_reset_expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }


    public function isUnit(): bool
    {
        return $this->role === 'unit';
    }

    public function isUmkm(): bool
    {
        return $this->role === 'umkm';
    }

    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }

    public function getFotoProfilUrlAttribute(): ?string
    {
        if ($this->foto_profil) {
            return asset('storage/' . $this->foto_profil);
        }
        return null;
    }

    public function isVerificationTokenValid(): bool
    {
        return $this->verification_token
            && $this->verification_token_expires_at
            && $this->verification_token_expires_at->isFuture();
    }

    public function isPasswordResetTokenValid(): bool
    {
        return $this->password_reset_token
            && $this->password_reset_expires_at
            && $this->password_reset_expires_at->isFuture();
    }

    public function unit(): HasOne
    {
        return $this->hasOne(Unit::class, 'user_id');
    }

    public function umkm(): HasOne
    {
        return $this->hasOne(Umkm::class, 'user_id');
    }

    public function createdUmkm(): HasMany
    {
        return $this->hasMany(Umkm::class, 'created_by');
    }

    public function verifiedUmkm(): HasMany
    {
        return $this->hasMany(Umkm::class, 'verified_by');
    }
}
