<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kota extends Model
{
    protected $guarded = [];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_code', 'code');
    }

    public function kecamatan(): HasMany
    {
        return $this->hasMany(Kecamatan::class, 'kota_code', 'code');
    }

    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'kota_code', 'code');
    }

    public function vendor(): HasMany
    {
        return $this->hasMany(Vendor::class, 'kota_code', 'code');
    }
}
