<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmkmRekening extends Model
{
    use HasFactory;

    protected $table = 'umkm_rekening';

    protected $fillable = [
        'umkm_id',
        'nama_bank',
        'nomor_rekening',
        'nama_rekening',
    ];

    public function umkm()
    {
        return $this->belongsTo(Umkm::class, 'umkm_id');
    }
}
