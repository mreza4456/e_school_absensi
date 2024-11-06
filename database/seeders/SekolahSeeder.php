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
        // Jawa Timur province code
        $provinsiCode = 35;

        // Kota/Kabupaten codes in Jawa Timur dengan timezone
        $kotaCodes = [
            [
                'code' => 3578, // Surabaya
                'timezone' => 'Asia/Jakarta' // WIB
            ],
            [
                'code' => 3573, // Malang
                'timezone' => 'Asia/Jakarta' // WIB
            ],
            [
                'code' => 3509, // Jember
                'timezone' => 'Asia/Jakarta' // WIB
            ],
            [
                'code' => 3525, // Gresik
                'timezone' => 'Asia/Jakarta' // WIB
            ],
            [
                'code' => 3515, // Sidoarjo
                'timezone' => 'Asia/Jakarta' // WIB
            ],
            [
                'code' => 3574, // Probolinggo
                'timezone' => 'Asia/Jakarta' // WIB
            ],
            [
                'code' => 3571, // Kediri
                'timezone' => 'Asia/Jakarta' // WIB
            ],
            [
                'code' => 3506, // Kediri (Kabupaten)
                'timezone' => 'Asia/Jakarta' // WIB
            ],
            [
                'code' => 3524, // Lamongan
                'timezone' => 'Asia/Jakarta' // WIB
            ],
            [
                'code' => 3516, // Mojokerto
                'timezone' => 'Asia/Jakarta' // WIB
            ]
        ];

        // Sample school data
        $schools = [
            [
                'nama' => 'SMA Negeri 5 Surabaya',
                'jenjang' => 'SMA',
                'kota_code' => 3578,
                'npsn' => 20532241
            ],
            [
                'nama' => 'SMK Negeri 1 Surabaya',
                'jenjang' => 'SMK',
                'kota_code' => 3578,
                'npsn' => 20532242
            ],
            [
                'nama' => 'SMP Negeri 1 Malang',
                'jenjang' => 'SMP',
                'kota_code' => 3573,
                'npsn' => 20532243
            ],
            [
                'nama' => 'SD Negeri Kepanjen 1',
                'jenjang' => 'SD',
                'kota_code' => 3573,
                'npsn' => 20532244
            ],
            [
                'nama' => 'SMA Negeri 1 Jember',
                'jenjang' => 'SMA',
                'kota_code' => 3509,
                'npsn' => 20532245
            ],
            [
                'nama' => 'SMK Negeri 2 Jember',
                'jenjang' => 'SMK',
                'kota_code' => 3509,
                'npsn' => 20532246
            ],
            [
                'nama' => 'SMP Negeri 1 Gresik',
                'jenjang' => 'SMP',
                'kota_code' => 3525,
                'npsn' => 20532247
            ],
            [
                'nama' => 'SD Islam Al-Azhar Gresik',
                'jenjang' => 'SD',
                'kota_code' => 3525,
                'npsn' => 20532248
            ],
            [
                'nama' => 'SMA Negeri 1 Sidoarjo',
                'jenjang' => 'SMA',
                'kota_code' => 3515,
                'npsn' => 20532249
            ],
            [
                'nama' => 'SMK Negeri 1 Sidoarjo',
                'jenjang' => 'SMK',
                'kota_code' => 3515,
                'npsn' => 20532250
            ],
            [
                'nama' => 'SMP Negeri 1 Probolinggo',
                'jenjang' => 'SMP',
                'kota_code' => 3574,
                'npsn' => 20532251
            ],
            [
                'nama' => 'SD Negeri Probolinggo 1',
                'jenjang' => 'SD',
                'kota_code' => 3574,
                'npsn' => 20532252
            ],
            [
                'nama' => 'SMA Negeri 1 Kediri',
                'jenjang' => 'SMA',
                'kota_code' => 3571,
                'npsn' => 20532253
            ],
            [
                'nama' => 'SMK Negeri 1 Kediri',
                'jenjang' => 'SMK',
                'kota_code' => 3571,
                'npsn' => 20532254
            ],
            [
                'nama' => 'SMP Negeri 1 Lamongan',
                'jenjang' => 'SMP',
                'kota_code' => 3524,
                'npsn' => 20532255
            ],
            [
                'nama' => 'SD Negeri Lamongan 1',
                'jenjang' => 'SD',
                'kota_code' => 3524,
                'npsn' => 20532256
            ],
            [
                'nama' => 'SMA Negeri 1 Mojokerto',
                'jenjang' => 'SMA',
                'kota_code' => 3516,
                'npsn' => 20532257
            ],
            [
                'nama' => 'SMK Negeri 1 Mojokerto',
                'jenjang' => 'SMK',
                'kota_code' => 3516,
                'npsn' => 20532258
            ],
            [
                'nama' => 'SMP Islam Terpadu Mojokerto',
                'jenjang' => 'SMP',
                'kota_code' => 3516,
                'npsn' => 20532259
            ],
            [
                'nama' => 'SD Muhammadiyah 1 Kediri',
                'jenjang' => 'SD',
                'kota_code' => 3506,
                'npsn' => 20532260
            ],
        ];

        foreach ($schools as $school) {
            $schoolId = Str::uuid();

            // Get timezone for the school's city
            $cityTimezone = collect($kotaCodes)->firstWhere('code', $school['kota_code'])['timezone'];

            // Convert times based on timezone
            $timeAdjustment = [
                'Asia/Jakarta' => 0, // WIB (no adjustment needed)
                'Asia/Makassar' => 1, // WITA (+1 hour)
                'Asia/Jayapura' => 2, // WIT (+2 hours)
            ];

            $adjustment = $timeAdjustment[$cityTimezone];

            // Insert school data
            DB::table('sekolahs')->insert([
                'id' => $schoolId,
                'npsn' => $school['npsn'],
                'nama' => $school['nama'],
                'jenjang' => $school['jenjang'],
                'nik_kepala' => '35' . str_pad(rand(1, 9999999), 7, '0', STR_PAD_LEFT),
                'nama_kepala' => 'Dr. ' . fake()->name(),
                'alamat' => fake()->streetAddress(),
                'provinsi_code' => $provinsiCode,
                'kota_code' => $school['kota_code'],
                'no_telp' => '0' . rand(81100000000, 81399999999),
                'email' => strtolower(str_replace(' ', '.', $school['nama'])) . '@sch.id',
                'timezone' => $cityTimezone,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Insert daily schedules for each school
            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            foreach ($days as $day) {
                $scheduleId = Str::uuid();

                if ($day == 'Minggu') {
                    // Sunday is holiday
                    DB::table('jadwal_harians')->insert([
                        'id' => $scheduleId,
                        'sekolah_id' => $schoolId,
                        'hari' => $day,
                        'is_libur' => true,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                    continue;
                }

                // Base times (WIB)
                $baseSchedule = [
                    'masuk' => '07:00:00',
                    'masuk_selesai' => '07:30:00',
                    'istirahat' => '10:00:00',
                    'istirahat_selesai' => '10:30:00',
                    'pulang' => $day == 'Sabtu' ? '12:00:00' : '15:00:00',
                    'pulang_selesai' => $day == 'Sabtu' ? '12:30:00' : '15:30:00',
                ];

                // Adjust times based on timezone if needed
                $schedule = [];
                foreach ($baseSchedule as $key => $time) {
                    $time = Carbon::createFromFormat('H:i:s', $time)
                        ->addHours($adjustment)
                        ->format('H:i:s');
                    $schedule[$key] = $time;
                }

                DB::table('jadwal_harians')->insert([
                    'id' => $scheduleId,
                    'sekolah_id' => $schoolId,
                    'hari' => $day,
                    'jam_masuk' => $schedule['masuk'],
                    'jam_masuk_selesai' => $schedule['masuk_selesai'],
                    'jam_istirahat' => $schedule['istirahat'],
                    'jam_istirahat_selesai' => $schedule['istirahat_selesai'],
                    'jam_pulang' => $schedule['pulang'],
                    'jam_pulang_selesai' => $schedule['pulang_selesai'],
                    'is_libur' => false,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
