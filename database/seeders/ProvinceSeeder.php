<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            [
                "code" => 11,
                "nama" => "ACEH"
              ],
              [
                "code" => 12,
                "nama" => "SUMATERA UTARA"
              ],
              [
                "code" => 13,
                "nama" => "SUMATERA BARAT"
              ],
              [
                "code" => 14,
                "nama" => "RIAU"
              ],
              [
                "code" => 15,
                "nama" => "JAMBI"
              ],
              [
                "code" => 16,
                "nama" => "SUMATERA SELATAN"
              ],
              [
                "code" => 17,
                "nama" => "BENGKULU"
              ],
              [
                "code" => 18,
                "nama" => "LAMPUNG"
              ],
              [
                "code" => 19,
                "nama" => "KEPULAUAN BANGKA BELITUNG"
              ],
              [
                "code" => 21,
                "nama" => "KEPULAUAN RIAU"
              ],
              [
                "code" => 31,
                "nama" => "DKI JAKARTA"
              ],
              [
                "code" => 32,
                "nama" => "JAWA BARAT"
              ],
              [
                "code" => 33,
                "nama" => "JAWA TENGAH"
              ],
              [
                "code" => 34,
                "nama" => "DAERAH ISTIMEWA YOGYAKARTA"
              ],
              [
                "code" => 35,
                "nama" => "JAWA TIMUR"
              ],
              [
                "code" => 36,
                "nama" => "BANTEN"
              ],
              [
                "code" => 51,
                "nama" => "BALI"
              ],
              [
                "code" => 52,
                "nama" => "NUSA TENGGARA BARAT"
              ],
              [
                "code" => 53,
                "nama" => "NUSA TENGGARA TIMUR"
              ],
              [
                "code" => 61,
                "nama" => "KALIMANTAN BARAT"
              ],
              [
                "code" => 62,
                "nama" => "KALIMANTAN TENGAH"
              ],
              [
                "code" => 63,
                "nama" => "KALIMANTAN SELATAN"
              ],
              [
                "code" => 64,
                "nama" => "KALIMANTAN TIMUR"
              ],
              [
                "code" => 65,
                "nama" => "KALIMANTAN UTARA"
              ],
              [
                "code" => 71,
                "nama" => "SULAWESI UTARA"
              ],
              [
                "code" => 72,
                "nama" => "SULAWESI TENGAH"
              ],
              [
                "code" => 73,
                "nama" => "SULAWESI SELATAN"
              ],
              [
                "code" => 74,
                "nama" => "SULAWESI TENGGARA"
              ],
              [
                "code" => 75,
                "nama" => "GORONTALO"
              ],
              [
                "code" => 76,
                "nama" => "SULAWESI BARAT"
              ],
              [
                "code" => 81,
                "nama" => "MALUKU"
              ],
              [
                "code" => 82,
                "nama" => "MALUKU UTARA"
              ],
              [
                "code" => 91,
                "nama" => "PAPUA"
              ],
              [
                "code" => 92,
                "nama" => "PAPUA BARAT"
              ],
              [
                "code" => 93,
                "nama" => "PAPUA SELATAN"
              ],
              [
                "code" => 94,
                "nama" => "PAPUA TENGAH"
              ],
              [
                "code" => 95,
                "nama" => "PAPUA PEGUNUNGAN"
              ]
        ];

        foreach ($provinces as $province) {
            DB::table('provinsis')->insert($province);
        }
    }
}
