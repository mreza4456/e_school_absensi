<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Members extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

    protected static function booted()
    {
        static::created(function ($siswa) {
            cache()->forget("siswa_options_{$siswa->sekolah_id}");
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function groups(): BelongsTo
    {
        return $this->belongsTo(Groups::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function uidType(): HasMany
    {
        return $this->hasMany(UidType::class);
    }
}
