<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use App\Models\Users;

class ModalUmkm extends Model
{
    protected $table = 'modal_umkm';

    protected $fillable = [
        'uuid',
        'umkm_id',
        'nama_item',
        'kategori_modal',
        'keterangan',
        'nilai_modal',
        'kondisi',
        'tanggal_perolehan',
        'foto',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'foto'               => 'array',
        'nilai_modal'        => 'integer',
        'tanggal_perolehan'  => 'date',
    ];

    // =============================================
    // BOOT — Auto generate UUID saat create
    // =============================================
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    // =============================================
    // RELATIONS
    // =============================================

    public function umkm(): BelongsTo
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'updated_by');
    }

    // =============================================
    // ACCESSORS
    // =============================================

    // Nilai modal dalam format Rupiah
    // Contoh: $modal->nilai_rupiah → "Rp 5.000.000"
    public function getNilaiRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->nilai_modal, 0, ',', '.');
    }

    // Foto pertama sebagai cover/thumbnail
    // Contoh: $modal->foto_cover → "modal_foto/gerobak-1.jpg"
    public function getFotoCoverAttribute(): string|null
    {
        $foto = $this->foto;
        return (!empty($foto)) ? $foto[0] : null;
    }

    // =============================================
    // SCOPES
    // =============================================

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByKategori($query, string $kategori)
    {
        return $query->where('kategori_modal', $kategori);
    }

    public function scopeByKondisi($query, string $kondisi)
    {
        return $query->where('kondisi', $kondisi);
    }
}