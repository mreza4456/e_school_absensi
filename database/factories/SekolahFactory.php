<?php

namespace Database\Factories;

use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Factories\Factory;

class SekolahFactory extends Factory
{
    protected $model = Sekolah::class;

    public function definition()
    {
        return [
            'id' => fake()->uuid(),
            'npsn' => fake()->unique()->numberBetween(10000000, 99999999),
            'nama' => 'Sekolah ' . fake()->company(),
            'jenjang' => fake()->randomElement(['SD', 'SMP', 'SMA', 'SMK']),
            'nik_kepala' => fake()->numerify('################'),
            'nama_kepala' => fake()->name(),
            'alamat' => fake()->address(),
            'provinsi_code' => 35,
            'kota_code' => 3578,
            'kecamatan_code' => 357806,
            'no_telp' => fake()->unique()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'logo' => null,
            'timezone' => fake()->randomElement(['WIB', 'WITA', 'WIT']),
            'status' => true,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure()
    {
        return $this->afterCreating(function (Sekolah $sekolah) {
            // Buat jadwal untuk setiap hari
            $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

            foreach ($hari as $index => $namaHari) {
                $isLibur = ($namaHari === 'Minggu'); // Set Minggu sebagai hari libur

                if ($isLibur) {
                    $sekolah->jadwalHarian()->create([
                        'id' => fake()->uuid(),
                        'hari' => $namaHari,
                        'is_libur' => true,
                        'jam_masuk' => null,
                        'jam_masuk_selesai' => null,
                        'jam_istirahat' => null,
                        'jam_istirahat_selesai' => null,
                        'jam_pulang' => null,
                        'jam_pulang_selesai' => null,
                    ]);
                } else {
                    // Jika Jumat, jam pulang lebih awal
                    $jamPulang = ($namaHari === 'Jumat') ? '11:30:00' : '15:00:00';
                    $jamPulangSelesai = ($namaHari === 'Jumat') ? '12:00:00' : '15:30:00';

                    $sekolah->jadwalHarian()->create([
                        'id' => fake()->uuid(),
                        'hari' => $namaHari,
                        'jam_masuk' => '07:00:00',
                        'jam_masuk_selesai' => '07:30:00',
                        'jam_istirahat' => '12:00:00',
                        'jam_istirahat_selesai' => '13:00:00',
                        'jam_pulang' => $jamPulang,
                        'jam_pulang_selesai' => $jamPulangSelesai,
                        'is_libur' => false,
                    ]);
                }
            }
        });
    }
}
