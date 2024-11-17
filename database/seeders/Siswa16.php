<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Siswa16 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
   {
       $siswas = [
           [
               'id' => Str::uuid(),
               'sekolah_id' => '9d749f41-3bf2-48da-9dc0-438d930e5c79',
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
               'kelas_id' => '9d77b3d0-fdee-4479-b089-ec3f33e523bd',
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
           ]
       ];

       DB::table('siswas')->insert($siswas);
   }
}
