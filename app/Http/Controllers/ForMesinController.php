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
            // Validasi input
            $validatedData = $request->validate([
                'kode_mesin' => 'required|string',
                'sekolah_id' => 'required|string',
                'uid' => 'required|string',
                'tanggal' => 'required|date',
                'waktu' => 'required|date_format:H:i:s'
            ]);

            // Cek apakah mesin dengan kode_mesin dan sekolah_id valid
            $mesin = Mesin::where('kode_mesin', $validatedData['kode_mesin'])
                ->where('sekolah_id', $validatedData['sekolah_id'])
                ->firstOrFail();

            // Ambil data sekolah
            $sekolah = Sekolah::findOrFail($validatedData['sekolah_id']);

            // Tentukan timezone sekolah
            $schoolTimezone = $this->getSchoolTimezone($sekolah);

            // Konversi input tanggal dan waktu ke timezone sekolah
            $inputDateTime = Carbon::parse(
                $validatedData['tanggal'] . ' ' . $validatedData['waktu'],
                $schoolTimezone
            );

            // Tentukan hari dalam Bahasa Indonesia
            $dayOfWeek = $inputDateTime->locale('id')->dayName;

            // Ambil jadwal harian sekolah
            $jadwal = $sekolah->jadwalHarian()
                ->where('hari', $dayOfWeek)
                ->firstOrFail();

            // Konversi waktu jadwal ke Carbon
            $jamMasuk = Carbon::parse(
                $inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk,
                $schoolTimezone
            );
            $jamMasukSelesai = Carbon::parse(
                $inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk_selesai,
                $schoolTimezone
            );
            $jamPulang = Carbon::parse(
                $inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_pulang,
                $schoolTimezone
            );
            $jamPulangSelesai = $jamPulang->copy()->addHour();

            // Tentukan keterangan absensi
            if ($inputDateTime->between($jamMasuk, $jamMasukSelesai)) {
                $keterangan = 'Masuk';
            } elseif ($inputDateTime->gt($jamMasukSelesai) && $inputDateTime->lt($jamPulang)) {
                $keterangan = 'Terlambat';
            } elseif ($inputDateTime->between($jamPulang, $jamPulangSelesai)) {
                $keterangan = 'Pulang';
            } else {
                throw new \Exception('Waktu absensi di luar jam sekolah.');
            }

            // Cek absensi yang sudah ada
            $existingAbsensi = Absensi::where('uid', $validatedData['uid'])
                ->where('tanggal', $inputDateTime->toDateString())
                ->where('sekolah_id', $validatedData['sekolah_id'])
                ->where(function ($query) use ($keterangan) {
                    $query->where('keterangan', $keterangan)
                        ->orWhere(function ($q) use ($keterangan) {
                            if ($keterangan === 'Masuk') {
                                $q->where('keterangan', 'Terlambat');
                            } elseif ($keterangan === 'Terlambat') {
                                $q->where('keterangan', 'Masuk');
                            }
                        });
                })
                ->first();

            if ($existingAbsensi) {
                if ($existingAbsensi->keterangan === $keterangan) {
                    throw new \Exception("Absensi {$keterangan} sudah ada untuk tanggal ini.");
                } else {
                    throw new \Exception("Sudah ada absensi {$existingAbsensi->keterangan} untuk tanggal ini.");
                }
            }

            // Buat data absensi baru
            $absensi = Absensi::create([
                'sekolah_id' => $validatedData['sekolah_id'],
                'uid' => $validatedData['uid'],
                'tanggal' => $inputDateTime->toDateString(),
                'waktu' => $inputDateTime->toTimeString(),
                'keterangan' => $keterangan,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Absensi berhasil dicatat',
                'data' => [
                    'id' => $absensi->id,
                    'sekolah_id' => $absensi->sekolah_id,
                    'uid' => $absensi->uid,
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
