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
    public function run()
    {
        $siswas = [
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '9d2401af',
                'nis' => '18509',
                'nama' => 'Adrian Haris Maulana',
                'panggilan' => 'Adrian Haris Maulana',
                'jk' => 'L',
                'telp_ortu' => '85141500000',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '463ee3bc',
                'nis' => '18573',
                'nama' => 'Samsul Arifin',
                'panggilan' => 'Samsul Arifin',
                'jk' => 'L',
                'telp_ortu' => '8327632764',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '0ba8ab3b',
                'nis' => '18540',
                'nama' => 'Mokhammad Nur Ramadhani',
                'panggilan' => 'Mokhammad Nur Ramadhani',
                'jk' => 'L',
                'telp_ortu' => '8525362532',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => 'f22902af',
                'nis' => '18569',
                'nama' => 'Rodhi Firmansyah Akhmad',
                'panggilan' => 'Rodhi Firmansyah Akhmad',
                'jk' => 'L',
                'telp_ortu' => '7813726362',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => 'f38601af',
                'nis' => '18570',
                'nama' => 'Royhan Akbar',
                'panggilan' => 'Royhan Akbar',
                'jk' => 'L',
                'telp_ortu' => '836236612',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '93f22334',
                'nis' => '18574',
                'nama' => 'Muhamad Fauzi Maulud Huda',
                'panggilan' => 'Muhamad Fauzi Maulud Huda',
                'jk' => 'L',
                'telp_ortu' => '85514152461',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => 'aa1cf481',
                'nis' => '18562',
                'nama' => 'Nailatul Nikmah',
                'panggilan' => 'Nailatul Nikmah',
                'jk' => 'P',
                'telp_ortu' => '851563561',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '6a790281',
                'nis' => '18515',
                'nama' => 'Ayu Rohmatul Hasanah',
                'panggilan' => 'Ayu Rohmatul Hasanah',
                'jk' => 'P',
                'telp_ortu' => '862635651',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '3a9fcb80',
                'nis' => '18545',
                'nama' => 'Muchammad Bagir Assegaf',
                'panggilan' => 'Muchammad Bagir Assegaf',
                'jk' => 'L',
                'telp_ortu' => '851314511',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => 'aa53d80',
                'nis' => '18572',
                'nama' => 'Salim Zakaria',
                'panggilan' => 'Salim Zakaria',
                'jk' => 'L',
                'telp_ortu' => '855156512',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '53bbc00d',
                'nis' => '18551',
                'nama' => 'Muhammad Ardiansyah',
                'panggilan' => 'Muhammad Ardiansyah',
                'jk' => 'L',
                'telp_ortu' => '8515651313',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => 'b3182e14',
                'nis' => '18511',
                'nama' => 'Akhmad Arsalan Naufal',
                'panggilan' => 'Akhmad Arsalan Naufal',
                'jk' => 'L',
                'telp_ortu' => '85155653356',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '33cf9235',
                'nis' => '18560',
                'nama' => 'Mukh. Izzul Islam',
                'panggilan' => 'Mukh. Izzul Islam',
                'jk' => 'L',
                'telp_ortu' => '86515300000',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '334cfd13',
                'nis' => '18513',
                'nama' => 'Alif Alfiyan Zakariyah',
                'panggilan' => 'Alif Alfiyan Zakariyah',
                'jk' => 'L',
                'telp_ortu' => '87163653651',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '83f5650f',
                'nis' => '18516',
                'nama' => 'Daffa Rayhan Pratama',
                'panggilan' => 'Daffa Rayhan Pratama',
                'jk' => 'L',
                'telp_ortu' => '86151526511',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'id' => Str::uuid(),
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'kelas_id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'uid' => '236a3434',
                'nis' => '18528',
                'nama' => 'Jaguar Rizky Kristanto',
                'panggilan' => 'Jaguar Rizky Kristanto',
                'jk' => 'L',
                'telp_ortu' => '8671365136',
                'status' => true,
                'deleted_at' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                "id" => "9d82f2cc-ae4b-46ba-9130-71abad68c8f8",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d82f22b-ea67-4981-ae24-a82a9cec8242",
                "uid" => "713tsb78b4",
                "nis" => 211311,
                "nama" => "M. Dio Rizky",
                "panggilan" => "Dio",
                "jk" => "L",
                "telp_ortu" => "08515161375641",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-17T08:30:48.000Z",
                "updated_at" => "2024-11-17T08:30:48.000Z"
            ],
            [
                "id" => "9d82f342-f7cd-4127-8c16-a9e08ececc82",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d82f22b-ea67-4981-ae24-a82a9cec8242",
                "uid" => "q36712yshu3",
                "nis" => 613876731,
                "nama" => "Syauqi Parma",
                "panggilan" => "Syauqi",
                "jk" => "P",
                "telp_ortu" => "081523534526",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-17T08:32:05.000Z",
                "updated_at" => "2024-11-17T08:32:05.000Z"
            ],
            [
                "id" => "9d82f36e-678e-4e63-807f-514fd8b385bf",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d82f289-ce6b-44e5-a9e1-4d42f1f17dce",
                "uid" => "n4278cryn7",
                "nis" => 37617461,
                "nama" => "Diandra Aqiel Bagaskara",
                "panggilan" => "Bagas",
                "jk" => "L",
                "telp_ortu" => "08216356235",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-17T08:32:34.000Z",
                "updated_at" => "2024-11-17T08:32:34.000Z"
            ],
            [
                "id" => "9d82f3c9-bc75-4f67-a431-34d772754336",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d82f289-ce6b-44e5-a9e1-4d42f1f17dce",
                "uid" => "xrr473jd",
                "nis" => 17382333,
                "nama" => "Syuqi Aripin",
                "panggilan" => "Syuqi",
                "jk" => "P",
                "telp_ortu" => "0823661531",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-17T08:33:33.000Z",
                "updated_at" => "2024-11-17T08:33:33.000Z"
            ]
        ];

        DB::table('siswas')->insert($siswas);
    }
}
