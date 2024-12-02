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
        unset($data['kelas_filter']);
        try {
            $user = Auth::user();
            assert($user instanceof User);

            if (!$user->hasRole('super_admin')) {
                if ($user->hasRole('admin_sekolah') || ($user->hasRole('staff_sekolah') && $user->sekolah_id)) {
                    $data['sekolah_id'] = $user->sekolah_id;
                }
            }

            // Daftar keterangan khusus yang tidak memperbolehkan absensi lain
            $specialAttendanceTypes = ['Izin', 'Sakit', 'Alpa'];

            // Cek apakah siswa sudah memiliki absensi khusus di tanggal yang sama
            $existingSpecialAbsensi = Absensi::where('uid', $data['uid'])
                ->where('tanggal', $data['tanggal'])
                ->where('sekolah_id', $data['sekolah_id'])
                ->whereIn('keterangan', $specialAttendanceTypes)
                ->first();

            // Jika sudah ada absensi khusus
            if ($existingSpecialAbsensi) {
                throw new \Exception("Siswa sudah tercatat {$existingSpecialAbsensi->keterangan} pada tanggal ini.");
            }

            // Jika keterangan adalah salah satu tipe khusus, cek duplikasi
            if (in_array($data['keterangan'], $specialAttendanceTypes)) {
                $existingAbsensi = Absensi::where('uid', $data['uid'])
                    ->where('tanggal', $data['tanggal'])
                    ->where('sekolah_id', $data['sekolah_id'])
                    ->where('keterangan', $data['keterangan'])
                    ->first();

                if ($existingAbsensi) {
                    throw new \Exception("Absensi {$data['keterangan']} sudah ada untuk tanggal ini.");
                }
            }

            // Jika keterangan tidak otomatis, maka lakukan pengecekan seperti sebelumnya
            if (empty($data['keterangan'])) {
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

                // Tentukan keterangan otomatis
                if ($inputDateTime->between($jamMasuk, $jamMasukSelesai)) {
                    $data['keterangan'] = 'Masuk';
                } elseif ($inputDateTime->gt($jamMasukSelesai) && $inputDateTime->lt($jamPulang)) {
                    $data['keterangan'] = 'Terlambat';
                } elseif ($inputDateTime->between($jamPulang, $jamPulangSelesai)) {
                    $data['keterangan'] = 'Pulang';
                } else {
                    throw new \Exception('Waktu absensi di luar jam sekolah.');
                }
            }

            // Check for existing attendance untuk keterangan Masuk dan Terlambat
            $existingAbsensi = Absensi::where('uid', $data['uid'])
                ->where('tanggal', $data['tanggal'])
                ->where('sekolah_id', $data['sekolah_id'])
                ->where(function ($query) use ($data) {
                    $query->where('keterangan', $data['keterangan'])
                        ->orWhere(function ($q) use ($data) {
                            if ($data['keterangan'] === 'Masuk') {
                                $q->where('keterangan', 'Terlambat');
                            } elseif ($data['keterangan'] === 'Terlambat') {
                                $q->where('keterangan', 'Masuk');
                            }
                        });
                })
                ->first();

            if ($existingAbsensi) {
                if ($existingAbsensi->keterangan === $data['keterangan']) {
                    throw new \Exception("Absensi {$data['keterangan']} sudah ada untuk tanggal ini.");
                } else {
                    throw new \Exception("Sudah ada absensi {$existingAbsensi->keterangan} untuk tanggal ini.");
                }
            }

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
