<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    private function generateNIS($sekolah_id, $tahun_masuk)
    {
        // Format NIS: 2 digit tahun masuk + 5 digit nomor urut
        $count = DB::table('siswas')
            ->where('sekolah_id', $sekolah_id)
            ->count();

        return intval($tahun_masuk . str_pad($count + 1, 5, '0', STR_PAD_LEFT));
    }

    private function generatePanggilan($nama)
    {
        $nama_parts = explode(' ', $nama);
        return $nama_parts[0]; // Mengambil nama pertama sebagai panggilan
    }

    public function run()
    {
        // Get faker instance with Indonesian locale
        $faker = \Faker\Factory::create('id_ID');

        // Get all classes with their school information
        $classes = DB::table('kelas')
            ->join('sekolahs', 'kelas.sekolah_id', '=', 'sekolahs.id')
            ->select('kelas.*', 'sekolahs.jenjang', 'sekolahs.id as sekolah_id')
            ->get();

        // Common Indonesian names
        $namaDepanLakiLaki = [
            'Ahmad', 'Muhammad', 'Budi', 'Deni', 'Eko', 'Fajar', 'Gilang', 'Hendra',
            'Irfan', 'Joko', 'Kevin', 'Lutfi', 'Naufal', 'Okta', 'Putra', 'Reza',
            'Surya', 'Taufik', 'Umar', 'Wahyu', 'Yuda', 'Zaki'
        ];

        $namaDepanPerempuan = [
            'Ani', 'Bintang', 'Citra', 'Dewi', 'Eka', 'Fitri', 'Gita', 'Hana',
            'Indah', 'Julia', 'Kartika', 'Linda', 'Maya', 'Nita', 'Olivia', 'Putri',
            'Ratna', 'Sari', 'Tari', 'Umi', 'Vina', 'Wati', 'Yani', 'Zahra'
        ];

        $namaBelakang = [
            'Wijaya', 'Kusuma', 'Susanto', 'Pratama', 'Saputra', 'Widodo', 'Nugroho',
            'Wibowo', 'Hidayat', 'Permana', 'Firmansyah', 'Santoso', 'Purnama',
            'Kurniawan', 'Setiawan', 'Prasetyo', 'Sugiarto', 'Hartono', 'Irawan'
        ];

        foreach ($classes as $class) {
            // Determine number of students based on class type
            $numStudents = 0;

            if (str_contains($class->nama, 'IPA')) {
                $numStudents = rand(30, 35);
            } elseif (str_contains($class->nama, 'IPS')) {
                $numStudents = rand(25, 30);
            } elseif (str_contains($class->nama, 'TKJ') ||
                      str_contains($class->nama, 'AKL') ||
                      str_contains($class->nama, 'OTKP')) {
                $numStudents = rand(20, 25);
            } else {
                $numStudents = rand(25, 30);
            }

            // Get entry year based on class name
            $classLevel = (int) filter_var($class->nama, FILTER_SANITIZE_NUMBER_INT);
            $entryYear = 0;

            switch ($class->jenjang) {
                case 'SD':
                    $entryYear = 24 - ($classLevel - 1);
                    break;
                case 'SMP':
                    $entryYear = 24 - ($classLevel - 7);
                    break;
                case 'SMA':
                case 'SMK':
                    $entryYear = 24 - ($classLevel - 10);
                    break;
            }

            // Generate students for this class
            for ($i = 0; $i < $numStudents; $i++) {
                $gender = rand(0, 1) ? 'L' : 'P';

                // Generate name based on gender
                if ($gender === 'L') {
                    $namaDepan = $namaDepanLakiLaki[array_rand($namaDepanLakiLaki)];
                } else {
                    $namaDepan = $namaDepanPerempuan[array_rand($namaDepanPerempuan)];
                }
                $namaBelakangPick = $namaBelakang[array_rand($namaBelakang)];

                // Sometimes add middle name
                $namaLengkap = rand(0, 1) ?
                    $namaDepan . ' ' . $namaBelakangPick :
                    $namaDepan . ' ' . $faker->firstName() . ' ' . $namaBelakangPick;

                DB::table('siswas')->insert([
                    'id' => Str::uuid(),
                    'sekolah_id' => $class->sekolah_id,
                    'kelas_id' => $class->id,
                    'uid' => null, // Assuming this will be filled later
                    'nis' => $this->generateNIS($class->sekolah_id, $entryYear),
                    'nama' => $namaLengkap,
                    'panggilan' => $this->generatePanggilan($namaLengkap),
                    'jk' => $gender,
                    'telp_ortu' => '08' . rand(1100000000, 1399999999), // Format: 08xxxxxxxxxx
                    'status' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }
}
