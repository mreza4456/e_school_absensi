<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use App\Models\Absensi;
use App\Models\Sekolah;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CreateAbsensi extends CreateRecord
{
    protected static string $resource = AbsensiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            $user = Auth::user();
            assert($user instanceof User);

            if (!$user->hasRole('super_admin')) {
                if ($user->hasRole('admin_sekolah') || ($user->hasRole('staff_sekolah') && $user->sekolah_id)) {
                    $data['sekolah_id'] = $user->sekolah_id;
                }
            }

            // Get the school's schedule for the given date
            $sekolah = Sekolah::findOrFail($data['sekolah_id']);

            // Get the timezone for the school
            $schoolTimezone = $this->getSchoolTimezone($sekolah);

            // Convert input date and time to school's timezone
            $inputDateTime = Carbon::parse($data['tanggal'] . ' ' . $data['waktu'], $schoolTimezone);

            // Determine the day of the week in Bahasa Indonesia
            $dayOfWeek = $inputDateTime->locale('id')->dayName;

            // Get school schedule
            $jadwal = $sekolah->jadwalHarian()->where('hari', $dayOfWeek)->firstOrFail();

            // Convert schedule times to Carbon instances for the same day in school's timezone
            $jamMasuk = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk, $schoolTimezone);
            $jamMasukSelesai = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk_selesai, $schoolTimezone);
            $jamPulang = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_pulang, $schoolTimezone);
            $jamPulangSelesai = $jamPulang->copy()->addHour();

            // Determine the attendance type based on time
            if ($inputDateTime->between($jamMasuk, $jamMasukSelesai)) {
                $keterangan = 'Masuk';
            } elseif ($inputDateTime->gt($jamMasukSelesai) && $inputDateTime->lt($jamPulang)) {
                $keterangan = 'Terlambat';
            } elseif ($inputDateTime->between($jamPulang, $jamPulangSelesai)) {
                $keterangan = 'Pulang';
            } else {
                throw new \Exception('Waktu absensi di luar jam sekolah.');
            }

            // Check for existing attendance
            $existingAbsensi = Absensi::where('uid', $data['uid'])
                ->where('tanggal', $inputDateTime->toDateString())
                ->where('sekolah_id', $data['sekolah_id'])
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

            // Set the determined attendance type and adjusted date/time
            $data['keterangan'] = $keterangan;
            $data['tanggal'] = $inputDateTime->toDateString();
            $data['waktu'] = $inputDateTime->toTimeString();

            return $data;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    private function getSchoolTimezone(Sekolah $sekolah): string
    {
        // Assuming there's a 'timezone' field in the Sekolah model
        // If not, you might need to determine this based on some other criteria
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
