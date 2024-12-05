<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Mesin;
use App\Models\Sekolah;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ForMesinController extends Controller
{
    public function mesin(Request $request): JsonResponse
    {
        try {
            // Validasi input mesin
            $validatedMesin = $request->validate([
                'kode_mesin' => 'required|string'
            ]);

            // Ambil data mesin dengan kode_mesin dan relasi yang dipilih
            $mesin = Mesin::with(['sekolah', 'wifi'])
                ->where('kode_mesin', $validatedMesin['kode_mesin'])
                ->firstOrFail();

            $transformedMesin = [
                'id' => $mesin->id,
                'vendor_id' => $mesin->vendor_id,
                'sekolah_id' => $mesin->sekolah_id,
                'kode_mesin' => $mesin->kode_mesin,
                'tgl_pembuatan' => $mesin->tgl_pembuatan,
                'idle' => $mesin->idle,
                'keterangan' => $mesin->keterangan,
                'status' => $mesin->status,
                'created_at' => $mesin->created_at,
                'updated_at' => $mesin->updated_at,
                'sekolah' => $mesin->sekolah ? [
                    'id' => $mesin->sekolah->id,
                    'npsn' => $mesin->sekolah->npsn,
                    'nama' => $mesin->sekolah->nama,
                    'alamat' => $mesin->sekolah->alamat,
                    'kota_code' => $mesin->sekolah->kota_code,
                    'provinsi_code' => $mesin->sekolah->provinsi_code,
                    'timezone' => $mesin->sekolah->timezone
                ] : null,
                'wifi' => $mesin->wifi ? [
                    'ssid' => $mesin->wifi->ssid,
                    'password' => $mesin->wifi->password
                ] : null
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Data mesin berhasil diambil',
                'data' => $transformedMesin
            ], 200);

        } catch (ValidationException $e) {
            // Tangani kesalahan validasi
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            // Tangani jika mesin tidak ditemukan
            return response()->json([
                'status' => 'error',
                'message' => 'Mesin tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            // Tangani kesalahan umum
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function siswa(Request $request): JsonResponse
    {
        try {
            // Validasi input
            $validatedData = $request->validate([
                'kode_mesin' => 'required|string',
                'sekolah_id' => 'required|string',
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            // Cek apakah mesin dengan kode_mesin dan sekolah_id valid
            $mesin = Mesin::where('kode_mesin', $validatedData['kode_mesin'])
                ->where('sekolah_id', $validatedData['sekolah_id'])
                ->firstOrFail();

            // Query siswa dengan relasi kelas
            $query = Siswa::where('sekolah_id', $validatedData['sekolah_id'])
                ->with(['kelas' => function($query) {
                    $query->select('id', 'nama_kelas');
                }])
                ->select(
                    'id',
                    'uid',
                    'nis',
                    'nama',
                    'panggilan',
                    'kelas_id',
                    'status',
                    'sekolah_id'
                );

            // Terapkan limit jika diberikan
            if (isset($validatedData['limit'])) {
                $query->limit($validatedData['limit']);
            }

            // Ambil data siswa
            $siswas = $query->get();

            // Transform data siswa
            $transformedSiswas = $siswas->map(function($siswa) {
                return [
                    'id' => $siswa->id,
                    'uid' => $siswa->uid,
                    'nis' => $siswa->nis,
                    'nama' => $siswa->nama,
                    'panggilan' => $siswa->panggilan,
                    'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : null,
                    'status' => $siswa->status,
                    'sekolah_id' => $siswa->sekolah_id,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data siswa berhasil diambil',
                'total' => $transformedSiswas->count(),
                'data' => $transformedSiswas
            ], 200);

        } catch (ValidationException $e) {
            // Tangani kesalahan validasi
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            // Tangani jika mesin atau data tidak ditemukan
            return response()->json([
                'status' => 'error',
                'message' => 'Mesin atau sekolah tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            // Tangani kesalahan umum
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function absensi(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'sekolah_id' => 'required|string',
                'siswa_id' => 'required|string',
                'tanggal' => 'required|date',
                'waktu' => 'required|date_format:H:i:s',
                'keterangan' => 'nullable|string'
            ]);

            // // Daftar keterangan khusus yang tidak memperbolehkan absensi lain
            // $specialAttendanceTypes = ['Izin', 'Sakit', 'Alpa'];

            // // Cek apakah siswa sudah memiliki absensi khusus di tanggal yang sama
            // $existingSpecialAbsensi = Absensi::where('siswa_id', $validatedData['siswa_id'])
            //     ->where('tanggal', $validatedData['tanggal'])
            //     ->where('sekolah_id', $validatedData['sekolah_id'])
            //     ->whereIn('keterangan', $specialAttendanceTypes)
            //     ->first();

            // if ($existingSpecialAbsensi) {
            //     throw new \Exception("Siswa sudah tercatat {$existingSpecialAbsensi->keterangan} pada tanggal ini.");
            // }

            // // Jika keterangan adalah salah satu tipe khusus, cek duplikasi
            // if (in_array($validatedData['keterangan'] ?? '', $specialAttendanceTypes)) {
            //     $existingAbsensi = Absensi::where('siswa_id', $validatedData['siswa_id'])
            //         ->where('tanggal', $validatedData['tanggal'])
            //         ->where('sekolah_id', $validatedData['sekolah_id'])
            //         ->where('keterangan', $validatedData['keterangan'])
            //         ->first();

            //     if ($existingAbsensi) {
            //         throw new \Exception("Absensi {$validatedData['keterangan']} sudah ada untuk tanggal ini.");
            //     }
            // }

            // // Jika keterangan tidak ada, maka tentukan otomatis
            // if (empty($validatedData['keterangan'])) {
            //     $sekolah = Sekolah::findOrFail($validatedData['sekolah_id']);
            //     $timezone = $this->getSchoolTimezone($sekolah);
            //     $inputDateTime = Carbon::parse("{$validatedData['tanggal']} {$validatedData['waktu']}", $timezone);
            //     $dayOfWeek = $inputDateTime->locale('id')->dayName;

            //     // Get school schedule
            //     $jadwal = $sekolah->jadwalHarian()->where('hari', $dayOfWeek)->firstOrFail();

            //     // Convert schedule times to Carbon instances
            //     $baseDate = $inputDateTime->format('Y-m-d');
            //     $jamMasuk = Carbon::parse("$baseDate {$jadwal->jam_masuk}", $timezone);
            //     $jamMasukSelesai = Carbon::parse("$baseDate {$jadwal->jam_masuk_selesai}", $timezone);
            //     $jamPulang = Carbon::parse("$baseDate {$jadwal->jam_pulang}", $timezone);
            //     $jamPulangSelesai = $jamPulang->copy()->addHour();

            //     // Tentukan keterangan otomatis
            //     if ($inputDateTime->between($jamMasuk, $jamMasukSelesai)) {
            //         $validatedData['keterangan'] = 'Masuk';
            //     } elseif ($inputDateTime->between($jamMasukSelesai, $jamPulang)) {
            //         $validatedData['keterangan'] = 'Terlambat';
            //     } elseif ($inputDateTime->between($jamPulang, $jamPulangSelesai)) {
            //         $validatedData['keterangan'] = 'Pulang';
            //     } else {
            //         throw new \Exception('Waktu absensi di luar jam sekolah.');
            //     }
            // }

            // // Cek duplikasi untuk keterangan Masuk dan Terlambat
            // $existingAbsensi = Absensi::where('siswa_id', $validatedData['siswa_id'])
            //     ->where('tanggal', $validatedData['tanggal'])
            //     ->where('sekolah_id', $validatedData['sekolah_id'])
            //     ->where(function ($query) use ($validatedData) {
            //         $query->where('keterangan', $validatedData['keterangan'])
            //             ->orWhere(function ($q) use ($validatedData) {
            //                 if ($validatedData['keterangan'] === 'Masuk') {
            //                     $q->where('keterangan', 'Terlambat');
            //                 } elseif ($validatedData['keterangan'] === 'Terlambat') {
            //                     $q->where('keterangan', 'Masuk');
            //                 }
            //             });
            //     })
            //     ->first();

            // if ($existingAbsensi) {
            //     $message = $existingAbsensi->keterangan === $validatedData['keterangan']
            //         ? "Absensi {$validatedData['keterangan']} sudah ada untuk tanggal ini."
            //         : "Sudah ada absensi {$existingAbsensi->keterangan} untuk tanggal ini.";
            //     throw new \Exception($message);
            // }

            // Create attendance record
            $absensi = Absensi::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Absensi berhasil dicatat',
                'data' => [
                    'id' => $absensi->id,
                    'sekolah_id' => $absensi->sekolah_id,
                    'tanggal' => $absensi->tanggal,
                    'waktu' => $absensi->waktu,
                    'keterangan' => $absensi->keterangan
                ]
            ], 201);

        } catch (ValidationException $e) {
            // Tangani kesalahan validasi
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            // Tangani jika mesin, sekolah, atau jadwal tidak ditemukan
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            // Tangani kesalahan umum
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getSchoolTimezone(Sekolah $sekolah): string
    {
        switch ($sekolah->timezone) {
            case 'WIB':
                return 'Asia/Jakarta';
            case 'WITA':
                return 'Asia/Makassar';
            case 'WIT':
                return 'Asia/Jayapura';
            default:
                return 'Asia/Jakarta'; // Default to WIB if not specified
        }
    }
}
