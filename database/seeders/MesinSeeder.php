<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mesinData = [
            [
                "id" => "9d75d974-271f-4996-a301-621af14992dd",
                "vendor_id" => "9d749fa6-cc37-4ce3-bbc1-6245249feb1c",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kode_mesin" => "d4d4da5c22ac",
                "tgl_pembuatan" => "2024-11-11",
                "idle" => 15,
                "keterangan" => "Sudah Aktif",
                "status" => true,
                "created_at" => "2024-11-10T20:14:10.000Z",
                "updated_at" => "2024-11-10T20:14:10.000Z"
            ]
        ];

        DB::table('mesins')->insert($mesinData);
    }
}
