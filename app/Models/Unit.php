<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class Unit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'admin_nama',
        'admin_telepon',
        'admin_email',
        'admin_foto',
        'nama_unit',
        'kode_unit',
        'logo',
        'provinsi_kode',
        'kota_kode',
        'kecamatan_kode',
        'kelurahan_kode',
        'provinsi_nama',
        'kota_nama',
        'kecamatan_nama',
        'kelurahan_nama',
        'kode_pos',
        'telepon',
        'unit_email',
        'deskripsi',
        'alamat',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
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

    public function provinsi()
    {
        return $this->belongsTo(Province::class, 'provinsi_kode', 'code');
    }

    public function kota()
    {
        return $this->belongsTo(City::class, 'kota_kode', 'code');
    }

    public function kecamatan()
    {
        return $this->belongsTo(District::class, 'kecamatan_kode', 'code');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Village::class, 'kelurahan_kode', 'code');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function umkm(): HasMany
    {
        return $this->hasMany(Umkm::class, 'unit_id');
    }

    public function umkmAktif(): HasMany
    {
        return $this->hasMany(Umkm::class, 'unit_id')->where('status', 'aktif');
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByProvinsi($query, $provinsiKode)
    {
        return $query->where('provinsi_kode', $provinsiKode);
    }

    public function scopeByKota($query, $kotaKode)
    {
        return $query->where('kota_kode', $kotaKode);
    }

    public function scopeByKecamatan($query, $kecamatanKode)
    {
        return $query->where('kecamatan_kode', $kecamatanKode);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_unit', 'like', "%{$keyword}%")
                ->orWhere('kode_unit', 'like', "%{$keyword}%");
        });
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    public function getAdminFotoUrlAttribute(): ?string
    {
        if ($this->admin_foto) {
            return asset('storage/' . $this->admin_foto);
        }
        return null;
    }

    public function getAlamatLengkapAttribute(): string
    {
        $parts = array_filter([
            $this->kelurahan?->name,
            $this->kecamatan?->name,
            $this->kota?->name,
            $this->provinsi?->name,
            $this->kode_pos ? "Kode Pos: {$this->kode_pos}" : null,
        ]);

        return implode(', ', $parts);
    }

    public function hasCompleteAddress(): bool
    {
        return !is_null($this->provinsi_kode)
            && !is_null($this->kota_kode)
            && !is_null($this->kecamatan_kode)
            && !is_null($this->kelurahan_kode);
    }

    public function hasAdmin(): bool
    {
        return !is_null($this->admin_nama)
            || !is_null($this->admin_email)
            || !is_null($this->admin_telepon);
    }

    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    public function toggleActive(): bool
    {
        return $this->update(['is_active' => !$this->is_active]);
    }

    public function syncWilayahNames(): void
    {
        $this->provinsi_nama = $this->provinsi?->name;
        $this->kota_nama = $this->kota?->name;
        $this->kecamatan_nama = $this->kecamatan?->name;
        $this->kelurahan_nama = $this->kelurahan?->name;

        $this->save();
    }

    public function getTotalUmkmAttribute(): int
    {
        return $this->umkm()->count();
    }

    public function getTotalUmkmAktifAttribute(): int
    {
        return $this->umkmAktif()->count();
    }
}
