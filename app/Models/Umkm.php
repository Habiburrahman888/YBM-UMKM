<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;
use App\Models\ProdukUmkm;
use App\Models\Pesanan;

class Umkm extends Model
{
    use HasFactory;

    protected $table = 'umkm';

    protected $fillable = [
        'uuid',
        'user_id',
        'unit_id',
        'kategori_id',
        'nama_pemilik',
        'nama_usaha',
        'tahun_berdiri',
        'telepon',
        'email',
        'alamat',
        'province_code',
        'city_code',
        'district_code',
        'village_code',
        'kode_pos',
        'logo_umkm',
        'tentang',
        'facebook',
        'instagram',
        'youtube',
        'tiktok',
        'kode_umkm',
        'tanggal_bergabung',
        'status',
        'created_by',
        'updated_by',
        'verified_at',
        'verified_by',
        'qris_foto',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
        'verified_at'       => 'datetime',
    ];

    protected $appends = [
        'status_badge',
        'alamat_lengkap',
        'lokasi_singkat',
    ];

    // ─── Route Key ───────────────────────────────────────────────────────────────

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    // ─── Relationships ───────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function produkUmkm()
    {
        return $this->hasMany(\App\Models\ProdukUmkm::class, 'umkm_id');
    }

    public function modalUmkm()
    {
        return $this->hasMany(ModalUmkm::class, 'umkm_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function creator()
    {
        return $this->belongsTo(Users::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Users::class, 'updated_by');
    }

    public function rekening()
    {
        return $this->hasMany(UmkmRekening::class, 'umkm_id');
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'umkm_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Users::class, 'verified_by');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'code');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_code', 'code');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────────

    public function scopeByUnit($query, $unitId)
    {
        return $query->where('unit_id', $unitId);
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'nonaktif');
    }

    public function scopeByProvince($query, $provinceCode)
    {
        return $query->where('province_code', $provinceCode);
    }

    public function scopeByCity($query, $cityCode)
    {
        return $query->where('city_code', $cityCode);
    }

    public function scopeByDistrict($query, $districtCode)
    {
        return $query->where('district_code', $districtCode);
    }

    public function scopeByVillage($query, $villageCode)
    {
        return $query->where('village_code', $villageCode);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_usaha', 'like', '%' . $search . '%')
                ->orWhere('nama_pemilik', 'like', '%' . $search . '%')
                ->orWhere('kode_umkm', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('telepon', 'like', '%' . $search . '%')
                ->orWhere('alamat', 'like', '%' . $search . '%')
                ->orWhereHas('kategori', function ($q) use ($search) {
                    $q->where('nama', 'like', '%' . $search . '%');
                })
                ->orWhereHas('province', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('city', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('district', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('village', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        });
    }

    // ─── Accessors ───────────────────────────────────────────────────────────────

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'aktif'    => '<span class="badge bg-success">Aktif</span>',
            'nonaktif' => '<span class="badge bg-danger">Nonaktif</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getAlamatLengkapAttribute()
    {
        $parts = [];

        if ($this->alamat) {
            $parts[] = $this->alamat;
        }

        if ($this->village) {
            $parts[] = 'Kel. ' . $this->village->name;
        }

        if ($this->district) {
            $parts[] = 'Kec. ' . $this->district->name;
        }

        if ($this->city) {
            $parts[] = $this->city->name;
        }

        if ($this->province) {
            $parts[] = $this->province->name;
        }

        if ($this->kode_pos) {
            $parts[] = $this->kode_pos;
        }

        return implode(', ', $parts) ?: '-';
    }

    public function getLokasiSingkatAttribute()
    {
        $parts = [];

        if ($this->city) {
            $parts[] = $this->city->name;
        }

        if ($this->province) {
            $parts[] = $this->province->name;
        }

        return implode(', ', $parts) ?: '-';
    }

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

    public function getTiktokAttribute($value)
    {
        if (!$value) return null;
        return (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) ? $value : 'https://' . $value;
    }

    // ─── Accessors: Total Modal ───────────────────────────────────────────────────

    // Total nilai seluruh modal UMKM
    // Contoh: $umkm->total_modal → "Rp 15.000.000"
    public function getTotalModalAttribute(): string
    {
        $total = $this->modalUmkm->sum('nilai_modal');
        return 'Rp ' . number_format($total, 0, ',', '.');
    }

    // ─── Helper Methods ───────────────────────────────────────────────────────────

    public function activate()
    {
        $this->update(['status' => 'aktif']);
        return $this;
    }

    public function deactivate()
    {
        $this->update(['status' => 'nonaktif']);
        return $this;
    }

    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    public function isInactive(): bool
    {
        return $this->status === 'nonaktif';
    }

    // ─── Boot ────────────────────────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = \Illuminate\Support\Str::uuid();
            }
        });
    }
}
