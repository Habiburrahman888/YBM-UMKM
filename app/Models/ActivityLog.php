<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'log_name',
        'causer_id',
        'causer_type',
        'causer_name',
        'causer_role',
        'event',
        'description',
        'subject_type',
        'subject_id',
        'subject_label',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    public function causer()
    {
        return $this->belongsTo(Users::class, 'causer_id');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeByEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    public function scopeByLogName($query, string $name)
    {
        return $query->where('log_name', $name);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('causer_id', $userId);
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('causer_role', $role);
    }

    public function scopeForSubject($query, string $type, int $id)
    {
        return $query->where('subject_type', $type)->where('subject_id', $id);
    }

    // ── Accessors ────────────────────────────────────────────────────────────

    public function getEventBadgeClassAttribute(): string
    {
        return match ($this->event) {
            'login'         => 'bg-green-100 text-green-700',
            'logout'        => 'bg-gray-100 text-gray-600',
            'create'        => 'bg-blue-100 text-blue-700',
            'update'        => 'bg-amber-100 text-amber-700',
            'delete'        => 'bg-red-100 text-red-700',
            'approve','verify' => 'bg-emerald-100 text-emerald-700',
            'reject'        => 'bg-orange-100 text-orange-700',
            'toggle_status' => 'bg-violet-100 text-violet-700',
            'export'        => 'bg-sky-100 text-sky-700',
            default         => 'bg-slate-100 text-slate-600',
        };
    }

    public function getEventIconAttribute(): string
    {
        return match ($this->event) {
            'login'    => '🔐',
            'logout'   => '🚪',
            'create'   => '➕',
            'update'   => '✏️',
            'delete'   => '🗑️',
            'approve'  => '✅',
            'reject'   => '❌',
            'verify'   => '🔍',
            'toggle_status' => '🔄',
            'export'   => '📥',
            default    => '📋',
        };
    }

    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'login'         => 'Login',
            'logout'        => 'Logout',
            'create'        => 'Buat',
            'update'        => 'Ubah',
            'delete'        => 'Hapus',
            'approve'       => 'Setujui',
            'reject'        => 'Tolak',
            'verify'        => 'Verifikasi',
            'toggle_status' => 'Ubah Status',
            'export'        => 'Export',
            default         => ucfirst($this->event),
        };
    }

    public function getSubjectLabelShortAttribute(): string
    {
        return match ($this->subject_type) {
            'App\\Models\\Umkm'      => 'UMKM',
            'App\\Models\\Unit'      => 'Unit',
            'App\\Models\\Users'     => 'User',
            'App\\Models\\ProdukUmkm' => 'Produk',
            'App\\Models\\Pesanan'   => 'Pesanan',
            'App\\Models\\Kategori'  => 'Kategori',
            'App\\Models\\ModalUmkm' => 'Modal',
            default                  => class_basename($this->subject_type ?? ''),
        };
    }
}
