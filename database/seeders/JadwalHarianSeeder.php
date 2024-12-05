<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JadwalHarianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jadwalHarianData = [
            [
                "id" => Str::uuid(),
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "hari" => "Senin",
                "jam_masuk" => "04:00:00Z",
                "jam_masuk_selesai" => "05:30:00Z",
                "jam_istirahat" => "10:30:00Z",
                "jam_istirahat_selesai" => "11:30:00Z",
                "jam_pulang" => "15:00:00Z",
                "jam_pulang_selesai" => "17:00:00Z",
                "is_libur" => false,
                "created_at" => "2024-11-10T05:35:36.000Z",
                "updated_at" => "2024-11-10T05:35:36.000Z"
            ],
            [
                "id" => Str::uuid(),
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "hari" => "Selasa",
                "jam_masuk" => "04:00:00Z",
                "jam_masuk_selesai" => "05:30:00Z",
                "jam_istirahat" => "10:30:00Z",
                "jam_istirahat_selesai" => "11:30:00Z",
                "jam_pulang" => "15:00:00Z",
                "jam_pulang_selesai" => "17:00:00Z",
                "is_libur" => false,
                "created_at" => "2024-11-10T05:35:36.000Z",
                "updated_at" => "2024-11-10T05:35:36.000Z"
            ],
            [
                "id" => Str::uuid(),
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "hari" => "Rabu",
                "jam_masuk" => "04:00:00Z",
                "jam_masuk_selesai" => "05:30:00Z",
                "jam_istirahat" => "10:30:00Z",
                "jam_istirahat_selesai" => "11:30:00Z",
                "jam_pulang" => "15:00:00Z",
                "jam_pulang_selesai" => "17:00:00Z",
                "is_libur" => false,
                "created_at" => "2024-11-10T05:35:36.000Z",
                "updated_at" => "2024-11-10T05:35:36.000Z"
            ],
            [
                "id" => Str::uuid(),
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "hari" => "Kamis",
                "jam_masuk" => "04:00:00Z",
                "jam_masuk_selesai" => "05:30:00Z",
                "jam_istirahat" => "10:30:00Z",
                "jam_istirahat_selesai" => "11:30:00Z",
                "jam_pulang" => "15:00:00Z",
                "jam_pulang_selesai" => "17:00:00Z",
                "is_libur" => false,
                "created_at" => "2024-11-10T05:35:36.000Z",
                "updated_at" => "2024-11-10T05:35:36.000Z"
            ],
            [
                "id" => Str::uuid(),
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "hari" => "Jumat",
                "jam_masuk" => "04:00:00Z",
                "jam_masuk_selesai" => "05:30:00Z",
                "jam_istirahat" => "10:30:00Z",
                "jam_istirahat_selesai" => "11:30:00Z",
                "jam_pulang" => "13:00:00Z",
                "jam_pulang_selesai" => "15:00:00Z",
                "is_libur" => false,
                "created_at" => "2024-11-10T05:35:36.000Z",
                "updated_at" => "2024-11-10T05:35:36.000Z"
            ],
            [
                "id" => Str::uuid(),
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "hari" => "Minggu",
                "jam_masuk" => null,
                "jam_masuk_selesai" => null,
                "jam_istirahat" => null,
                "jam_istirahat_selesai" => null,
                "jam_pulang" => null,
                "jam_pulang_selesai" => null,
                "is_libur" => true,
                "created_at" => "2024-11-10T05:35:36.000Z",
                "updated_at" => "2024-11-10T05:35:36.000Z"
            ],
            [
                "id" => Str::uuid(),
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "hari" => "Sabtu",
                "jam_masuk" => null,
                "jam_masuk_selesai" => null,
                "jam_istirahat" => null,
                "jam_istirahat_selesai" => null,
                "jam_pulang" => null,
                "jam_pulang_selesai" => null,
                "is_libur" => true,
                "created_at" => "2024-11-10T05:35:36.000Z",
                "updated_at" => "2024-11-18T20:16:27.000Z"
            ]
        ];

        DB::table('jadwal_harians')->insert($jadwalHarianData);
    }
}
