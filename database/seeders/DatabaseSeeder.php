<?php

namespace Database\Seeders;

use App\Models\Mesin;
use App\Models\Sekolah;
use App\Models\User;
use App\Models\Vendor;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinceSeeder::class,
            RegencySeeder::class,
            SekolahSeeder::class,
            VendorSeeder::class,
            UserSeeder::class,
            RoleSeeder::class,
            ModelHasRoleSeeder::class,
            PermissionSeeder::class,
            RoleHasPermissionSeeder::class,
            MesinSeeder::class,
            WifiSeeder::class,
            JadwalHarianSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class
        ]);
    }
}
