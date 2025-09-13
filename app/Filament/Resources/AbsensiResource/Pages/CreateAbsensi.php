<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use App\Models\Absensi;
use App\Models\Organization;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CreateAbsensi extends CreateRecord
{
    protected static string $resource = AbsensiResource::class;
    public function getTitle(): string
{
    return __('Create Attendance');
}

 

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['groups_filter']);
        // dd($data);
        try {
            $user = Auth::user();
            assert($user instanceof User);

            if (!$user->hasRole('super_admin')) {
                if ($user->hasRole('admin_organization') || ($user->hasRole('staff_organization') && $user->organization_id)) {
                    $data['organization_id'] = $user->organization_id;
                }
            }

            // Daftar keterangan khusus yang tidak memperbolehkan absensi lain
            $specialAttendanceTypes = ['Permit', 'Sick', 'Alpha'];

            // Cek apakah siswa sudah memiliki absensi khusus di tanggal yang sama
            $existingSpecialAbsensi = Absensi::where('members_id', $data['members_id'])
                ->where('tanggal', $data['tanggal'])
                ->where('organization_id', $data['organization_id'])
                ->whereIn('keterangan', $specialAttendanceTypes)
                ->first();

            // Jika sudah ada absensi khusus
            if ($existingSpecialAbsensi) {
                throw new \Exception("Members Already Register {$existingSpecialAbsensi->keterangan} pada tanggal ini.");
            }

            // Jika keterangan adalah salah satu tipe khusus, cek duplikasi
            if (in_array($data['keterangan'], $specialAttendanceTypes)) {
                $existingAbsensi = Absensi::where('members_id', $data['members_id'])
                    ->where('tanggal', $data['tanggal'])
                    ->where('organization_id', $data['organization_id'])
                    ->where('keterangan', $data['keterangan'])
                    ->first();

                if ($existingAbsensi) {
                    throw new \Exception("Attendance {$data['keterangan']} Already Exist On This Date.");
                }
            }

            // Jika keterangan tidak otomatis, maka lakukan pengecekan seperti sebelumnya
            if (empty($data['keterangan'])) {
                // Get the school's schedule for the given date
                $organization = Organization::findOrFail($data['organization_id']);

                // Get the timezone for the school
                $schoolTimezone = $this->getSchoolTimezone($organization);

                // Convert input date and time to school's timezone
                $inputDateTime = Carbon::parse($data['tanggal'] . ' ' . $data['waktu'], $schoolTimezone);

                // Determine the day of the week in Bahasa Indonesia
                $dayOfWeek = $inputDateTime->locale('id')->dayName;

                // Get school schedule
                $jadwal = $organization->jadwalHarian()->where('hari', $dayOfWeek)->firstOrFail();

                // Convert schedule times to Carbon instances for the same day in school's timezone
                $jamMasuk = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk, $schoolTimezone);
                $jamMasukSelesai = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk_selesai, $schoolTimezone);
                $jamPulang = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_pulang, $schoolTimezone);
                $jamPulangSelesai = $jamPulang->copy()->addHour();

                // Tentukan keterangan otomatis
                if ($inputDateTime->between($jamMasuk, $jamMasukSelesai)) {
                    $data['keterangan'] = 'Present';
                } elseif ($inputDateTime->gt($jamMasukSelesai) && $inputDateTime->lt($jamPulang)) {
                    $data['keterangan'] = 'Late';
                } elseif ($inputDateTime->between($jamPulang, $jamPulangSelesai)) {
                    $data['keterangan'] = 'Go Home';
                } else {
                    throw new \Exception('Absence time outside school hours');
                }
            }

            // Check for existing attendance untuk keterangan Masuk dan Terlambat
            $existingAbsensi = Absensi::where('members_id', $data['members_id'])
                ->where('tanggal', $data['tanggal'])
                ->where('organization_id', $data['organization_id'])
                ->where(function ($query) use ($data) {
                    $query->where('keterangan', $data['keterangan'])
                        ->orWhere(function ($q) use ($data) {
                            if ($data['keterangan'] === 'Present') {
                                $q->where('keterangan', 'Late');
                            } elseif ($data['keterangan'] === 'Late') {
                                $q->where('keterangan', 'Present');
                            }
                        });
                })
                ->first();

            if ($existingAbsensi) {
                if ($existingAbsensi->keterangan === $data['keterangan']) {
                    throw new \Exception("Attendance {$data['keterangan']} Already Exist On This Date.");
                } else {
                    throw new \Exception("Attendance Already Exist  {$existingAbsensi->keterangan} On This Date");
                }
            }

            return $data;
        } catch (\Exception $e) {
            // dd($e);
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    private function getSchoolTimezone(organization $organization): string
    {
        // Assuming there's a 'timezone' field in the organization model
        // If not, you might need to determine this based on some other criteria
        switch ($organization->timezone) {
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
