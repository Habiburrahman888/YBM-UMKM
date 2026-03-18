<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'uuid',
        'umkm_id',
        'produk_id',
        'nama_pembeli',
        'telepon_pembeli',
        'alamat_pembeli',
        'jumlah',
        'total_harga',
        'bukti_transfer',
        'status',
        'catatan',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }

    public function produk()
    {
        return $this->belongsTo(ProdukUmkm::class, 'produk_id');
    }

    public function items()
    {
        return $this->hasMany(PesananItem::class, 'pesanan_id');
    }
}
