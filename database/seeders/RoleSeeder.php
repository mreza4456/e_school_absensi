<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleData = [
            [
                "id" => 1,
                "name" => "super_admin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 2,
                "name" => "panel_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 3,
                "name" => "staff",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:21:22.000Z",
                "updated_at" => "2024-11-10T05:21:22.000Z"
            ],
            [
                "id" => 4,
                "name" => "vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:23:29.000Z",
                "updated_at" => "2024-11-10T05:23:29.000Z"
            ],
            [
                "id" => 5,
                "name" => "admin_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:23:59.000Z",
                "updated_at" => "2024-11-10T05:23:59.000Z"
            ],
            [
                "id" => 6,
                "name" => "staff_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:26:33.000Z",
                "updated_at" => "2024-11-10T05:26:33.000Z"
            ]
        ];

        DB::table('roles')->insert($roleData);
    }
}
