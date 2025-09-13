<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mesin extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected static function booted(): void
    {
        // Event untuk set keterangan
        static::creating(function (Mesin $mesin) {
            static::setKeterangan($mesin);
        });

        static::updating(function (Mesin $mesin) {
            static::setKeterangan($mesin);
        });

        // Event untuk create wifi
        static::created(function (Mesin $mesin) {
            $mesin->wifi()->create([
                'ssid' => 'eSchool',
                'password' => '12345678'
            ]);
        });
    }

    private static function setKeterangan(Mesin $mesin): void
    {
        if ($mesin->status) {
            $mesin->keterangan = $mesin->organizaion_id ? 'Sudah Aktif' : 'Belum Diset';
        } else {
            $mesin->keterangan = 'Tidak Aktif';
        }
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function organizaion(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function wifi(): HasOne
    {
        return $this->hasOne(Wifi::class);
    }
}
