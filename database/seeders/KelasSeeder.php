<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Get all sekolah IDs
        $sekolahIds = DB::table('sekolahs')->select('id', 'jenjang', 'nama')->get();

        foreach ($sekolahIds as $sekolah) {
            // Different class patterns based on school level
            switch ($sekolah->jenjang) {
                case 'SD':
                    $tingkatan = range(1, 6); // SD has 6 grades
                    $parallels = range('A', 'C'); // 3 parallel classes
                    foreach ($tingkatan as $tingkat) {
                        foreach ($parallels as $parallel) {
                            DB::table('kelas')->insert([
                                'id' => Str::uuid(),
                                'sekolah_id' => $sekolah->id,
                                'nama' => $tingkat . ' ' . $parallel,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }
                    }
                    break;

                case 'SMP':
                    $tingkatan = range(7, 9); // SMP has 3 grades (7-9)
                    $parallels = range('A', 'D'); // 4 parallel classes
                    foreach ($tingkatan as $tingkat) {
                        foreach ($parallels as $parallel) {
                            DB::table('kelas')->insert([
                                'id' => Str::uuid(),
                                'sekolah_id' => $sekolah->id,
                                'nama' => $tingkat . ' ' . $parallel,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }
                    }
                    break;

                case 'SMA':
                    $tingkatan = range(10, 12); // SMA has 3 grades (10-12)
                    $programs = [
                        'IPA' => range('A', 'C'), // 3 parallel classes for Science
                        'IPS' => range('A', 'B')  // 2 parallel classes for Social
                    ];
                    foreach ($tingkatan as $tingkat) {
                        foreach ($programs as $program => $parallels) {
                            foreach ($parallels as $parallel) {
                                DB::table('kelas')->insert([
                                    'id' => Str::uuid(),
                                    'sekolah_id' => $sekolah->id,
                                    'nama' => $tingkat . ' ' . $program . ' ' . $parallel,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }
                        }
                    }
                    break;

                case 'SMK':
                    $tingkatan = range(10, 12); // SMK has 3 grades (10-12)
                    // Common SMK programs
                    $programs = [
                        'TKJ' => range('A', 'B'),  // Teknik Komputer dan Jaringan
                        'AKL' => range('A', 'B'),  // Akuntansi dan Keuangan Lembaga
                        'OTKP' => range('A', 'B'), // Otomatisasi dan Tata Kelola Perkantoran
                    ];
                    foreach ($tingkatan as $tingkat) {
                        foreach ($programs as $program => $parallels) {
                            foreach ($parallels as $parallel) {
                                DB::table('kelas')->insert([
                                    'id' => Str::uuid(),
                                    'sekolah_id' => $sekolah->id,
                                    'nama' => $tingkat . ' ' . $program . ' ' . $parallel,
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]);
                            }
                        }
                    }
                    break;
            }
        }
    }
}
