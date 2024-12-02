<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Provinsi;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SekolahSeeder extends Seeder
{
    public function run()
    {
        $sekolahData = [
            [
                "id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "npsn" => 2637131,
                "nama" => "SMKN 1 PASURUAN",
                "jenjang" => "SMK",
                "nik_kepala" => "13378174",
                "nama_kepala" => "Budiono Siregar",
                "alamat" => "Jl. Pagesangan",
                "provinsi_code" => 35,
                "kota_code" => 3575,
                "no_telp" => "08535165136",
                "email" => "smkn1pasuruan@gmail.com",
                "logo" => null,
                "timezone" => "WIB",
                "status" => true,
                "deleted_at" => null,
                "created_at" => now(),
                "updated_at" => now()
            ]
        ];

        DB::table('sekolahs')->insert($sekolahData);
    }
}
