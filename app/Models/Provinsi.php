<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    protected $guarded = [];

    public function kota(): HasMany
    {
        return $this->hasMany(Kota::class, 'provinsi_code', 'code');
    }

    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'provinsi_code', 'code');
    }

    public function vendor(): HasMany
    {
        return $this->hasMany(Vendor::class, 'provinsi_code', 'code');
    }
}
