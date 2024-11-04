<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SekolahSeeder extends Seeder
{
    public function run()
    {
        // $sekolahs = collect(range(1, 16))->map(function ($i) {
        //     $provinsiCode = Provinsi::pluck('code')->random();
        //     $kotaCode = Kota::where('provinsi_code', $provinsiCode)->pluck('code')->random();
        //     $kecamatanCode = Kecamatan::where('kota_code', $kotaCode)->pluck('code')->random();

        //     return [
        //         'id' => Str::uuid(),
        //         'npsn' => 20103640 + $i,
        //         'nama' => "SMAN $i Jakarta",
        //         'jenjang' => 'SMA',
        //         'nik_kepala' => '317106230462000' . $i,
        //         'nama_kepala' => 'Dr. H. Suharto, M.Pd',
        //         'alamat' => 'Jl. Raden Saleh Raya No.30, Cikini',
        //         'provinsi_code' => $provinsiCode,
        //         'kota_code' => $kotaCode,
        //         'kecamatan_code' => $kecamatanCode,
        //         'no_telp' => '(021) 314444' . $i,
        //         'email' => 'sman' . $i . 'jakarta@gmail.com',
        //         'timezone' => 'WIB',
        //         'jam_masuk' => '06:30:00',
        //         'jam_masuk_selesai' => '07:00:00',
        //         'jam_istirahat' => '12:00:00',
        //         'jam_istirahat_selesai' => '12:45:00',
        //         'jam_pulang' => '15:00:00',
        //         'jam_pulang_selesai' => '15:30:00',
        //         'status' => true
        //     ];
        // })->toArray();


        // foreach ($sekolahs as $sekolah) {
        //     DB::table('sekolahs')->insert($sekolah);
        // }
        DB::table('sekolahs')->insert(
            [
                'id' => Str::uuid(),
                'npsn' => 20103640,
                'nama' => "SMKN 2 Surabaya",
                'jenjang' => 'SMK',
                'nik_kepala' => '317106230462000',
                'nama_kepala' => 'Dr. H. Suharto, M.Pd',
                'alamat' => 'Jl. Tentara Genie 45',
                'provinsi_code' => 35,
                'kota_code' => 3578,
                'kecamatan_code' => 351801,
                'no_telp' => '(021) 314444',
                'email' => 'smkn2surabaya@gmail.com',
                'timezone' => 'WIB',
                'jam_masuk' => '06:30:00',
                'jam_masuk_selesai' => '07:00:00',
                'jam_istirahat' => '12:00:00',
                'jam_istirahat_selesai' => '12:45:00',
                'jam_pulang' => '15:00:00',
                'jam_pulang_selesai' => '15:30:00',
                'status' => true
            ],
            [
                'id' => Str::uuid(),
                'npsn' => 20103641,
                'nama' => "SMKN 1 Surabaya",
                'jenjang' => 'SMK',
                'nik_kepala' => '317106230462001',
                'nama_kepala' => 'Dr. H. Suharto, M.Pd',
                'alamat' => 'Jl. Purwodadi',
                'provinsi_code' => 35,
                'kota_code' => 3514,
                'kecamatan_code' => 351401,
                'no_telp' => '(021) 314445',
                'email' => 'smkn1pasuruan@gmail.com',
                'timezone' => 'WIT',
                'jam_masuk' => '06:30:00',
                'jam_masuk_selesai' => '07:00:00',
                'jam_istirahat' => '12:00:00',
                'jam_istirahat_selesai' => '12:45:00',
                'jam_pulang' => '15:00:00',
                'jam_pulang_selesai' => '15:30:00',
                'status' => true
            ],
        );
    }
}
