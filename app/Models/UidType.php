<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UidType extends Model
{
    use HasUuids;

    protected $guarded = ['id'];

    public function members(): BelongsTo
    {
        return $this->belongsTo(Members::class);
    }
}
