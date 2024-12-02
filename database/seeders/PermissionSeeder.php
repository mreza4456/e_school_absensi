<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionData = [
            [
                "id" => 1,
                "name" => "view_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 2,
                "name" => "view_any_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 3,
                "name" => "create_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 4,
                "name" => "update_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 5,
                "name" => "restore_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 6,
                "name" => "restore_any_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 7,
                "name" => "replicate_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 8,
                "name" => "reorder_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 9,
                "name" => "delete_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 10,
                "name" => "delete_any_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 11,
                "name" => "force_delete_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 12,
                "name" => "force_delete_any_absensi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 13,
                "name" => "view_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 14,
                "name" => "view_any_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 15,
                "name" => "create_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 16,
                "name" => "update_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 17,
                "name" => "restore_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 18,
                "name" => "restore_any_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 19,
                "name" => "replicate_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 20,
                "name" => "reorder_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 21,
                "name" => "delete_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 22,
                "name" => "delete_any_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 23,
                "name" => "force_delete_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 24,
                "name" => "force_delete_any_kelas",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 25,
                "name" => "view_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 26,
                "name" => "view_any_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 27,
                "name" => "create_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 28,
                "name" => "update_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 29,
                "name" => "restore_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 30,
                "name" => "restore_any_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 31,
                "name" => "replicate_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 32,
                "name" => "reorder_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 33,
                "name" => "delete_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 34,
                "name" => "delete_any_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 35,
                "name" => "force_delete_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 36,
                "name" => "force_delete_any_kota",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 37,
                "name" => "view_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 38,
                "name" => "view_any_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 39,
                "name" => "create_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 40,
                "name" => "update_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 41,
                "name" => "restore_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 42,
                "name" => "restore_any_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 43,
                "name" => "replicate_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 44,
                "name" => "reorder_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 45,
                "name" => "delete_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 46,
                "name" => "delete_any_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 47,
                "name" => "force_delete_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 48,
                "name" => "force_delete_any_mesin",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 49,
                "name" => "view_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 50,
                "name" => "view_any_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 51,
                "name" => "create_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 52,
                "name" => "update_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 53,
                "name" => "restore_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 54,
                "name" => "restore_any_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 55,
                "name" => "replicate_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 56,
                "name" => "reorder_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 57,
                "name" => "delete_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 58,
                "name" => "delete_any_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 59,
                "name" => "force_delete_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 60,
                "name" => "force_delete_any_provinsi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 61,
                "name" => "view_role",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 62,
                "name" => "view_any_role",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 63,
                "name" => "create_role",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 64,
                "name" => "update_role",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 65,
                "name" => "delete_role",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 66,
                "name" => "delete_any_role",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 67,
                "name" => "view_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 68,
                "name" => "view_any_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 69,
                "name" => "create_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 70,
                "name" => "update_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 71,
                "name" => "restore_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 72,
                "name" => "restore_any_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 73,
                "name" => "replicate_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 74,
                "name" => "reorder_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 75,
                "name" => "delete_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 76,
                "name" => "delete_any_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 77,
                "name" => "force_delete_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 78,
                "name" => "force_delete_any_sekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 79,
                "name" => "view_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 80,
                "name" => "view_any_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 81,
                "name" => "create_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 82,
                "name" => "update_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 83,
                "name" => "restore_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:30.000Z",
                "updated_at" => "2024-11-10T05:18:30.000Z"
            ],
            [
                "id" => 84,
                "name" => "restore_any_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 85,
                "name" => "replicate_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 86,
                "name" => "reorder_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 87,
                "name" => "delete_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 88,
                "name" => "delete_any_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 89,
                "name" => "force_delete_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 90,
                "name" => "force_delete_any_siswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 91,
                "name" => "view_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 92,
                "name" => "view_any_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 93,
                "name" => "create_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 94,
                "name" => "update_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 95,
                "name" => "restore_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 96,
                "name" => "restore_any_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 97,
                "name" => "replicate_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 98,
                "name" => "reorder_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 99,
                "name" => "delete_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 100,
                "name" => "delete_any_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 101,
                "name" => "force_delete_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 102,
                "name" => "force_delete_any_user",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 103,
                "name" => "view_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 104,
                "name" => "view_any_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 105,
                "name" => "create_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 106,
                "name" => "update_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 107,
                "name" => "restore_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 108,
                "name" => "restore_any_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 109,
                "name" => "replicate_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 110,
                "name" => "reorder_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 111,
                "name" => "delete_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 112,
                "name" => "delete_any_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 113,
                "name" => "force_delete_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 114,
                "name" => "force_delete_any_vendor",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 115,
                "name" => "view_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 116,
                "name" => "view_any_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 117,
                "name" => "create_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 118,
                "name" => "update_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 119,
                "name" => "restore_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 120,
                "name" => "restore_any_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 121,
                "name" => "replicate_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 122,
                "name" => "reorder_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 123,
                "name" => "delete_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 124,
                "name" => "delete_any_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 125,
                "name" => "force_delete_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 126,
                "name" => "force_delete_any_wifi",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 127,
                "name" => "widget_VendorMesinOverview",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 128,
                "name" => "widget_GlobalStatsOverview",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 129,
                "name" => "widget_SekolahOverview",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 130,
                "name" => "widget_SekolahChart",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 131,
                "name" => "widget_SekolahKelasSiswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 132,
                "name" => "widget_SiswaTerlambatChart",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 133,
                "name" => "widget_VendorChart",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 134,
                "name" => "widget_JenisKelaminSiswa",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 135,
                "name" => "widget_UserDistributionChart",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 136,
                "name" => "widget_MesinSekolah",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 137,
                "name" => "widget_MesinMonitoringTable",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 138,
                "name" => "widget_SiswaPalingSeringTerlambat",
                "guard_name" => "web",
                "created_at" => "2024-11-10T05:18:31.000Z",
                "updated_at" => "2024-11-10T05:18:31.000Z"
            ],
            [
                "id" => 139,
                "name" => "widget_SuperAdminStaffSekolahChart",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:11:03.000Z",
                "updated_at" => "2024-11-12T20:11:03.000Z"
            ],
            [
                "id" => 140,
                "name" => "widget_SuperAdminStaffVendorChart",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:11:03.000Z",
                "updated_at" => "2024-11-12T20:11:03.000Z"
            ],
            [
                "id" => 141,
                "name" => "widget_SuperAdminStaffStatsOverview",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:11:03.000Z",
                "updated_at" => "2024-11-12T20:11:03.000Z"
            ],
            [
                "id" => 142,
                "name" => "widget_SuperAdminStaffMesinTable",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:11:03.000Z",
                "updated_at" => "2024-11-12T20:11:03.000Z"
            ],
            [
                "id" => 143,
                "name" => "widget_SuperAdminStaffUserChart",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:11:03.000Z",
                "updated_at" => "2024-11-12T20:11:03.000Z"
            ],
            [
                "id" => 144,
                "name" => "widget_AdminStaffSekolahStatsOverview",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:46:13.000Z",
                "updated_at" => "2024-11-12T20:46:13.000Z"
            ],
            [
                "id" => 145,
                "name" => "widget_AdminStaffSekolahSiswaTerlambat",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:46:13.000Z",
                "updated_at" => "2024-11-12T20:46:13.000Z"
            ],
            [
                "id" => 146,
                "name" => "widget_AdminStaffSekolahSiswaSeringTerlambat",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:46:13.000Z",
                "updated_at" => "2024-11-12T20:46:13.000Z"
            ],
            [
                "id" => 147,
                "name" => "widget_AdminStaffSekolahSiswaJkChart",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:46:13.000Z",
                "updated_at" => "2024-11-12T20:46:13.000Z"
            ],
            [
                "id" => 148,
                "name" => "widget_AdminStaffSekolahSiswaKelas",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:46:13.000Z",
                "updated_at" => "2024-11-12T20:46:13.000Z"
            ],
            [
                "id" => 149,
                "name" => "widget_AdminStaffSekolahMesinChart",
                "guard_name" => "web",
                "created_at" => "2024-11-12T20:46:13.000Z",
                "updated_at" => "2024-11-12T20:46:13.000Z"
            ]
        ];

        DB::table('permissions')->insert($permissionData);
    }
}
