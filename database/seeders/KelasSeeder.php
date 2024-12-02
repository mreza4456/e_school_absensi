<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $kelasData = [
            [
                'id' => '9d74a53b-70a6-4661-b1e5-a1331395d106',
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'nama_kelas' => 'XII RPL 2',
                'created_at' => '2024-11-10T05:52:19.000Z',
                'updated_at' => '2024-11-10T05:52:19.000Z'
            ],
            [
                'id' => '9d82f22b-ea67-4981-ae24-a82a9cec8242',
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'nama_kelas' => 'XII RPL 1',
                'created_at' => '2024-11-17T08:29:02.000Z',
                'updated_at' => '2024-11-17T08:29:02.000Z'
            ],
            [
                'id' => '9d82f289-ce6b-44e5-a9e1-4d42f1f17dce',
                'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
                'nama_kelas' => 'XII RPL 3',
                'created_at' => '2024-11-17T08:30:04.000Z',
                'updated_at' => '2024-11-17T08:30:04.000Z'
            ]
        ];

        DB::table('kelas')->insert($kelasData);
    }
}
