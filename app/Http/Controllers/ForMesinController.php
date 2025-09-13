<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Mesin;
use App\Models\Organization;
use App\Models\Member;
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
            $validatedMesin = $request->validate([
                'kode_mesin' => 'required|string'
            ]);

            $mesin = Mesin::with(['organization', 'wifi'])
                ->where('kode_mesin', $validatedMesin['kode_mesin'])
                ->firstOrFail();

            $transformedMesin = [
                'id' => $mesin->id,
                'vendor_id' => $mesin->vendor_id,
                'organization_id' => $mesin->organization_id,
                'kode_mesin' => $mesin->kode_mesin,
                'tgl_pembuatan' => $mesin->tgl_pembuatan,
                'idle' => $mesin->idle,
                'keterangan' => $mesin->keterangan,
                'status' => $mesin->status,
                'created_at' => $mesin->created_at,
                'updated_at' => $mesin->updated_at,
                'organization' => $mesin->organization ? [
                    'id' => $mesin->organization->id,
                    'code' => $mesin->organization->code,
                    'nama' => $mesin->organization->nama,
                    'alamat' => $mesin->organization->alamat,
                    'kota_code' => $mesin->organization->kota_code,
                    'provinsi_code' => $mesin->organization->provinsi_code,
                    'timezone' => $mesin->organization->timezone
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
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mesin tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function members(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'kode_mesin' => 'required|string',
                'organization_id' => 'required|string',
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            $mesin = Mesin::where('kode_mesin', $validatedData['kode_mesin'])
                ->where('organization_id', $validatedData['organization_id'])
                ->firstOrFail();

            $query = Member::where('organization_id', $validatedData['organization_id'])
                ->with(['groups' => function($query) {
                    $query->select('id', 'nama_group');
                }])
                ->select(
                    'id',
                    'uid',
                    'nis',
                    'nama',
                    'panggilan',
                    'group_id',
                    'status',
                    'organization_id'
                );

            if (isset($validatedData['limit'])) {
                $query->limit($validatedData['limit']);
            }

            $members = $query->get();

            $transformedMembers = $members->map(function($member) {
                return [
                    'id' => $member->id,
                    'uid' => $member->uid,
                    'nis' => $member->nis,
                    'nama' => $member->nama,
                    'panggilan' => $member->panggilan,
                    'group' => $member->groups ? $member->groups->nama_group : null,
                    'status' => $member->status,
                    'organization_id' => $member->organization_id,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data members berhasil diambil',
                'total' => $transformedMembers->count(),
                'data' => $transformedMembers
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Mesin atau organization tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
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
                'organization_id' => 'required|string',
                'member_id' => 'required|string',
                'tanggal' => 'required|date',
                'waktu' => 'required|date_format:H:i:s',
                'keterangan' => 'nullable|string'
            ]);

            $absensi = Absensi::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'Absensi berhasil dicatat',
                'data' => [
                    'id' => $absensi->id,
                    'organization_id' => $absensi->organization_id,
                    'tanggal' => $absensi->tanggal,
                    'waktu' => $absensi->waktu,
                    'keterangan' => $absensi->keterangan
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getOrganizationTimezone(Organization $organization): string
    {
        switch ($organization->timezone) {
            case 'WIB':
                return 'Asia/Jakarta';
            case 'WITA':
                return 'Asia/Makassar';
            case 'WIT':
                return 'Asia/Jayapura';
            default:
                return 'Asia/Jakarta';
        }
    }
}
