<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Sekolah extends Model
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
            return $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')");
        } else {
            return $query->orderByRaw("CASE
                WHEN hari = 'Senin' THEN 1
                WHEN hari = 'Selasa' THEN 2
                WHEN hari = 'Rabu' THEN 3
                WHEN hari = 'Kamis' THEN 4
                WHEN hari = 'Jumat' THEN 5
                WHEN hari = 'Sabtu' THEN 6
                WHEN hari = 'Minggu' THEN 7
                ELSE 8
            END");
        }
    }
    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class);
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
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
