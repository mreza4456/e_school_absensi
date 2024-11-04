<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wifi extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function mesin(): BelongsTo
    {
        return $this->belongsTo(Mesin::class);
    }
}
