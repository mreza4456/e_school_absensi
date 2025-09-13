<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Organization extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_code', 'code');
    }

    public function kota(): BelongsTo
    {
        return $this->belongsTo(Kota::class, 'kota_code', 'code');
    }

    public function jadwalHarian(): HasMany
    {
        $query = $this->hasMany(JadwalHarian::class);

        if (DB::connection()->getDriverName() === 'mysql') {
            return $query->orderByRaw("FIELD(hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')");
        } else {
            return $query->orderByRaw("CASE
                WHEN hari = 'Monday' THEN 1
                WHEN hari = 'Tuesday' THEN 2
                WHEN hari = 'Wednesday' THEN 3
                WHEN hari = 'Thursday' THEN 4
                WHEN hari = 'Friday' THEN 5
                WHEN hari = 'Saturday' THEN 6
                WHEN hari = 'Sunday' THEN 7
                ELSE 8
            END");
        }
    }
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Members::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Groups::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function mesin(): HasMany
    {
        return $this->hasMany(Mesin::class);
    }
}
