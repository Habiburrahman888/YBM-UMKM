<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class ProdukUmkm extends Model
{
    use HasFactory;

    protected $table = 'produk_umkm';

    protected $fillable = [
        'uuid',
        'umkm_id',
        'nama_produk',
        'deskripsi_produk',
        'foto_produk',
        'harga',
        'kategori_satuan',
        'stok',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'foto_produk' => 'array',
        'harga'       => 'decimal:2',
    ];

    // -----------------------------------------------------------------------
    // BOOT — auto generate UUID saat create
    // -----------------------------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    }

    // -----------------------------------------------------------------------
    // RELATIONS
    // -----------------------------------------------------------------------

    /**
     * Produk ini milik satu UMKM (one-to-one inverse).
     */
    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    /**
     * User yang pertama kali menginput produk ini.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User yang terakhir mengupdate produk ini.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // -----------------------------------------------------------------------
    // ACCESSORS
    // -----------------------------------------------------------------------

    /**
     * Return foto pertama sebagai thumbnail.
     */
    public function getThumbnailAttribute(): ?string
    {
        $fotos = $this->foto_produk;
        return (!empty($fotos)) ? asset('storage/' . $fotos[0]) : null;
    }

    /**
     * Return semua foto sebagai full URL (bukan path).
     */
    public function getFotoUrlsAttribute(): array
    {
        return collect($this->foto_produk)
            ->map(fn($path) => asset('storage/' . $path))
            ->all();
    }

    /**
     * Format harga ke Rupiah.
     */
    public function getHargaRupiahAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // -----------------------------------------------------------------------
    // SCOPES
    // -----------------------------------------------------------------------

    /**
     * Filter berdasarkan nama produk.
     */
    public function scopeSearch($query, ?string $keyword)
    {
        return $query->when(
            $keyword,
            fn($q) => $q->where('nama_produk', 'LIKE', '%' . $keyword . '%')
        );
    }

    /**
     * Filter berdasarkan range harga.
     */
    public function scopeHargaAntara($query, ?float $min, ?float $max)
    {
        return $query
            ->when($min, fn($q) => $q->where('harga', '>=', $min))
            ->when($max, fn($q) => $q->where('harga', '<=', $max));
    }
}