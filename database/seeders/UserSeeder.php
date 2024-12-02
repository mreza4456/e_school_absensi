<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                "id" => "9d74a0cc-d7e4-4b14-b172-0b427c938d49",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "vendor_id" => null,
                "name" => "Admin SMKN 1 Pasuruan",
                "email" => "adminsmkn1pasuruan@gmail.com",
                "email_verified_at" => null,
                "password" => bcrypt('12345678'),
                "avatar_url" => null,
                "status" => true,
                "remember_token" => null,
                "created_at" => "2024-11-10T05:39:55.000Z",
                "updated_at" => "2024-11-10T05:39:55.000Z"
            ],
            [
                "id" => "9d74a1a2-07af-4adc-bdd8-92c109254c6e",
                "sekolah_id" => "9d749f41-3bf2-48da-9dc0-438d930e5c79",
                "vendor_id" => null,
                "name" => "Staff SMKN 1 Pasuruan",
                "email" => "staffsmkn1pasuruan@gmail.com",
                "email_verified_at" => null,
                "password" => bcrypt('12345678'),
                "avatar_url" => null,
                "status" => true,
                "remember_token" => null,
                "created_at" => "2024-11-10T05:42:15.000Z",
                "updated_at" => "2024-11-10T05:42:15.000Z"
            ],
            [
                "id" => "9d74a1dc-800f-44dc-8ed3-559cb47d35e6",
                "sekolah_id" => null,
                "vendor_id" => "9d749fa6-cc37-4ce3-bbc1-6245249feb1c",
                "name" => "Vendor Universal Big Data",
                "email" => "vendorubig@gmail.com",
                "email_verified_at" => null,
                "password" => bcrypt('12345678'),
                "avatar_url" => null,
                "status" => true,
                "remember_token" => null,
                "created_at" => "2024-11-10T05:42:53.000Z",
                "updated_at" => "2024-11-10T05:42:53.000Z"
            ],
            [
                "id" => "9d74a046-fac2-4561-8fb1-d4e74e812bbd",
                "sekolah_id" => null,
                "vendor_id" => null,
                "name" => "Staff",
                "email" => "staff@gmail.com",
                "email_verified_at" => null,
                "password" => bcrypt('12345678'),
                "avatar_url" => null,
                "status" => true,
                "remember_token" => null,
                "created_at" => "2024-11-10T05:38:27.000Z",
                "updated_at" => "2024-11-17T19:17:21.000Z"
            ],
            [
                "id" => "9d7498ac-c672-4138-bfe1-7ade04a7aab5",
                "sekolah_id" => null,
                "vendor_id" => null,
                "name" => "Hnzsama",
                "email" => "hnzsama@gmail.com",
                "email_verified_at" => null,
                "password" => bcrypt('12345678'),
                "avatar_url" => "users-profiles\/01JCYH7F7FA95MVZYB9MJ4P734.png",
                "status" => true,
                "remember_token" => null,
                "created_at" => "2024-11-10T05:17:12.000Z",
                "updated_at" => "2024-11-17T19:22:04.000Z"
            ]
        ];

        DB::table('users')->insert($userData);
    }
}
