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
                "id" => "2d9711a6-b73c-4195-a91e-72c6998461f1",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18509,
                "nama" => "Adrian Haris Maulana",
                "panggilan" => "Adrian Haris Maulana",
                "jk" => "L",
                "telp_ortu" => "85141500000",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "122e2b43-1ce6-43f7-850f-8daeda437560",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18573,
                "nama" => "Samsul Arifin",
                "panggilan" => "Samsul Arifin",
                "jk" => "L",
                "telp_ortu" => "8327632764",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "f5497890-c026-4541-9ba0-404f130f6b19",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18540,
                "nama" => "Mokhammad Nur Ramadhani",
                "panggilan" => "Mokhammad Nur Ramadhani",
                "jk" => "L",
                "telp_ortu" => "8525362532",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "caa651d7-c1c4-462d-b0e0-361d024ed048",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18569,
                "nama" => "Rodhi Firmansyah Akhmad",
                "panggilan" => "Rodhi Firmansyah Akhmad",
                "jk" => "L",
                "telp_ortu" => "7813726362",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "1557a961-368a-47b3-8db9-472d4d49e6a6",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18570,
                "nama" => "Royhan Akbar",
                "panggilan" => "Royhan Akbar",
                "jk" => "L",
                "telp_ortu" => "836236612",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "a8d4eaa3-333e-426b-8f52-95ab30edbdd1",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18574,
                "nama" => "Muhamad Fauzi Maulud Huda",
                "panggilan" => "Muhamad Fauzi Maulud Huda",
                "jk" => "L",
                "telp_ortu" => "85514152461",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "3ae5cca1-2b7f-44ff-a755-182b79112950",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18562,
                "nama" => "Nailatul Nikmah",
                "panggilan" => "Nailatul Nikmah",
                "jk" => "P",
                "telp_ortu" => "851563561",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "bb0f12c8-fc4b-466d-946a-7a5c2b4c9c1c",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18515,
                "nama" => "Ayu Rohmatul Hasanah",
                "panggilan" => "Ayu Rohmatul Hasanah",
                "jk" => "P",
                "telp_ortu" => "862635651",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "8e57e964-0ba9-4f9b-bf7c-c501fc3e54cc",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18545,
                "nama" => "Muchammad Bagir Assegaf",
                "panggilan" => "Muchammad Bagir Assegaf",
                "jk" => "L",
                "telp_ortu" => "851314511",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "f66a8dd7-a6a9-41d7-94ca-9c57bb39b09f",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18572,
                "nama" => "Salim Zakaria",
                "panggilan" => "Salim Zakaria",
                "jk" => "L",
                "telp_ortu" => "855156512",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "41a8c545-06bc-4723-bdb5-9de808a820f3",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18551,
                "nama" => "Muhammad Ardiansyah",
                "panggilan" => "Muhammad Ardiansyah",
                "jk" => "L",
                "telp_ortu" => "8515651313",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "eca61132-9eeb-4c93-93c0-67f9c1ac4c55",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18511,
                "nama" => "Akhmad Arsalan Naufal",
                "panggilan" => "Akhmad Arsalan Naufal",
                "jk" => "L",
                "telp_ortu" => "85155653356",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "11791dd5-a02a-4a84-9aaa-a0fa4817d907",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18560,
                "nama" => "Mukh. Izzul Islam",
                "panggilan" => "Mukh. Izzul Islam",
                "jk" => "L",
                "telp_ortu" => "86515300000",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "a323d7e4-19ab-4523-8755-bc4bc6b6ada8",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18513,
                "nama" => "Alif Alfiyan Zakariyah",
                "panggilan" => "Alif Alfiyan Zakariyah",
                "jk" => "L",
                "telp_ortu" => "87163653651",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "dd2911d5-3699-4f77-9f27-248a2cea81ad",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18516,
                "nama" => "Daffa Rayhan Pratama",
                "panggilan" => "Daffa Rayhan Pratama",
                "jk" => "L",
                "telp_ortu" => "86151526511",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "4d83fc13-b6c4-4bd8-8779-fe815ca21e3b",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d74a53b-70a6-4661-b1e5-a1331395d106",
                "nis" => 18528,
                "nama" => "Jaguar Rizky Kristanto",
                "panggilan" => "Jaguar Rizky Kristanto",
                "jk" => "L",
                "telp_ortu" => "8671365136",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-19T20:04:59.000Z",
                "updated_at" => "2024-11-19T20:04:59.000Z"
            ],
            [
                "id" => "9d82f2cc-ae4b-46ba-9130-71abad68c8f8",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d82f22b-ea67-4981-ae24-a82a9cec8242",
                "nis" => 211311,
                "nama" => "M. Dio Rizky",
                "panggilan" => "Dio",
                "jk" => "L",
                "telp_ortu" => "08515161375641",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-17T01:30:48.000Z",
                "updated_at" => "2024-11-17T01:30:48.000Z"
            ],
            [
                "id" => "9d82f342-f7cd-4127-8c16-a9e08ececc82",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d82f22b-ea67-4981-ae24-a82a9cec8242",
                "nis" => 613876731,
                "nama" => "Syauqi Parma",
                "panggilan" => "Syauqi",
                "jk" => "P",
                "telp_ortu" => "081523534526",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-17T01:32:05.000Z",
                "updated_at" => "2024-11-17T01:32:05.000Z"
            ],
            [
                "id" => "9d82f36e-678e-4e63-807f-514fd8b385bf",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d82f289-ce6b-44e5-a9e1-4d42f1f17dce",
                "nis" => 37617461,
                "nama" => "Diandra Aqiel Bagaskara",
                "panggilan" => "Bagas",
                "jk" => "L",
                "telp_ortu" => "08216356235",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-17T01:32:34.000Z",
                "updated_at" => "2024-11-17T01:32:34.000Z"
            ],
            [
                "id" => "9d82f3c9-bc75-4f67-a431-34d772754336",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "kelas_id" => "9d82f289-ce6b-44e5-a9e1-4d42f1f17dce",
                "nis" => 17382333,
                "nama" => "Syuqi Aripin",
                "panggilan" => "Syuqi",
                "jk" => "P",
                "telp_ortu" => "0823661531",
                "status" => true,
                "deleted_at" => null,
                "created_at" => "2024-11-17T01:33:33.000Z",
                "updated_at" => "2024-11-17T01:33:33.000Z"
            ]
        ];

        DB::table('siswas')->insert($siswas);
    }
}
