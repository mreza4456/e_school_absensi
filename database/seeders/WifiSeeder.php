<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WifiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wifiData = [
            [
                "id" => "9d75d974-2cae-4acb-a8b1-5382d658f67a",
                "mesin_id" => "9d75d974-271f-4996-a301-621af14992dd",
                "ssid" => "eSchool",
                "password" => "12345678",
                "created_at" => "2024-11-10T20:14:10.000Z",
                "updated_at" => "2024-11-10T20:14:10.000Z"
            ]
        ];

        DB::table('wifis')->insert($wifiData);
    }
}
