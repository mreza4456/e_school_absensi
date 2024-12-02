<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendorData = [
            [
                "id" => "9d749fa6-cc37-4ce3-bbc1-6245249feb1c",
                "nama" => "UNIVERSAL BIG DATA",
                "email" => "suratkita@gmail.com",
                "no_telp" => "08139791331",
                "alamat" => "Tasikmadu",
                "provinsi_code" => 35,
                "kota_code" => 3573,
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-10T05:36:42.000Z",
                "updated_at" => "2024-11-10T05:36:42.000Z"
            ]
        ];

        DB::table('vendors')->insert($vendorData);
    }
}
