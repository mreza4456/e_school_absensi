<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kecamatan extends Model
{
    protected $guarded = [];

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kota_code', 'code');
    }
}
