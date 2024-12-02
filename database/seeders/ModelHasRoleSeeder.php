<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelHasRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modelHasRoleData = [
            [
                "role_id" => 2,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d74a046-fac2-4561-8fb1-d4e74e812bbd"
            ],
            [
                "role_id" => 3,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d74a046-fac2-4561-8fb1-d4e74e812bbd"
            ],
            [
                "role_id" => 2,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d74a0cc-d7e4-4b14-b172-0b427c938d49"
            ],
            [
                "role_id" => 5,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d74a0cc-d7e4-4b14-b172-0b427c938d49"
            ],
            [
                "role_id" => 2,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d74a1a2-07af-4adc-bdd8-92c109254c6e"
            ],
            [
                "role_id" => 6,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d74a1a2-07af-4adc-bdd8-92c109254c6e"
            ],
            [
                "role_id" => 2,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d74a1dc-800f-44dc-8ed3-559cb47d35e6"
            ],
            [
                "role_id" => 4,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d74a1dc-800f-44dc-8ed3-559cb47d35e6"
            ],
            [
                "role_id" => 2,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d7498ac-c672-4138-bfe1-7ade04a7aab5"
            ],
            [
                "role_id" => 1,
                "model_type" => "App\\Models\\User",
                "model_id" => "9d7498ac-c672-4138-bfe1-7ade04a7aab5"
            ]
        ];

        DB::table('model_has_roles')->insert($modelHasRoleData);
    }
}
