<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use App\Models\JadwalHarian;
use App\Models\Siswa;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AdminStaffSekolahAbsensiChart extends ChartWidget
{
    use HasWidgetShield;
    
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Absensi';

    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        if (request()->is('admin')) {
            return false;
        }
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasRole(['admin_sekolah', 'staff_sekolah']);
    }

    protected function getDateRange(): array
    {
        $dateRange = $this->filters['dateRange'] ?? 'hari_ini';

        $now = Carbon::now();

        return match ($dateRange) {
            'hari_ini' => [
                $now->startOfDay(),
                $now->copy()->endOfDay(),
            ],
            'kemarin' => [
                $now->copy()->subDay()->startOfDay(),
                $now->copy()->subDay()->endOfDay(),
            ],
            '7_hari_terakhir' => [
                $now->copy()->subDays(6)->startOfDay(),
                $now->endOfDay(),
            ],
            '30_hari_terakhir' => [
                $now->copy()->subDays(29)->startOfDay(),
                $now->endOfDay(),
            ],
            'bulan_ini' => [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ],
            'bulan_lalu' => [
                $now->copy()->subMonth()->startOfMonth(),
                $now->copy()->subMonth()->endOfMonth(),
            ],
            default => [
                $now->copy()->subDays(6)->startOfDay(),
                $now->endOfDay(),
            ],
        };
    }

    protected function getData(): array
    {
        [$startDate, $endDate] = $this->getDateRange();
        $kelas_id = $this->filters['kelas'] ?? null;
        $sekolah_id = Auth::user()->sekolah_id;

        // Query untuk siswa berdasarkan kelas dan sekolah
        $siswaQuery = Siswa::where('sekolah_id', $sekolah_id);
        if ($kelas_id) {
            $siswaQuery->where('kelas_id', $kelas_id);
        }
        $siswa = $siswaQuery->get();

        // Query untuk absensi dengan filter tanggal dan sekolah
        $absensi = Absensi::query()
            ->where('sekolah_id', $sekolah_id)
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Add filter untuk kelas jika dipilih
        if ($kelas_id) {
            $absensi->whereHas('siswa', function (Builder $query) use ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            });
        }

        // Menghitung setiap status absensi
        $hadir = (clone $absensi)->where('keterangan', 'Masuk')->count();
        $terlambat = (clone $absensi)->where('keterangan', 'Terlambat')->count();
        $sakit = (clone $absensi)->where('keterangan', 'Sakit')->count();
        $izin = (clone $absensi)->where('keterangan', 'Izin')->count();

        // Mencari siswa yang tidak memiliki absensi dalam periode yang ditentukan
        $siswaTanpaAbsensi = $siswa->filter(function ($siswa) use ($startDate, $endDate, $sekolah_id) {
            // Ambil hari aktif berdasarkan sekolah
            $activeDays = JadwalHarian::where('sekolah_id', $sekolah_id)
                ->where('is_libur', false)
                ->pluck('hari')
                ->toArray();

            // Map hari menjadi format bahasa Inggris
            $dayMapping = [
                'Senin' => 'Monday',
                'Selasa' => 'Tuesday',
                'Rabu' => 'Wednesday',
                'Kamis' => 'Thursday',
                'Jumat' => 'Friday',
                'Sabtu' => 'Saturday',
                'Minggu' => 'Sunday',
            ];
            $activeEnglishDays = array_map(fn($day) => $dayMapping[$day] ?? null, $activeDays);

            // Iterasi tanggal dalam rentang waktu
            $period = Carbon::parse($startDate)->toPeriod($endDate);

            foreach ($period as $date) {
                // Cek apakah hari ini termasuk hari aktif
                if (!in_array($date->format('l'), $activeEnglishDays)) {
                    continue; // Skip jika hari libur
                }

                // Cek apakah siswa memiliki absensi pada hari ini
                $hasAbsensi = $siswa->absensi()
                    ->whereDate('tanggal', $date->format('Y-m-d'))
                    ->exists();

                if ($hasAbsensi) {
                    return false; // Tidak Alpa jika ada absensi
                }
            }

            return true; // Alpa jika tidak ada absensi di hari aktif
        });

        // Hitung jumlah siswa Alpa
        $alpa = $siswaTanpaAbsensi->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status Kehadiran Siswa',
                    'data' => [$hadir, $terlambat, $sakit, $izin, $alpa],
                    'backgroundColor' => [
                        'rgba(251, 146, 60, 0.7)',  // Orange-400
                        'rgba(249, 115, 22, 0.7)',  // Orange-500
                        'rgba(234, 88, 12, 0.7)',   // Orange-600
                        'rgba(194, 65, 12, 0.7)',   // Orange-700
                        'rgba(239, 68, 68, 0.7)',   // Custom Red-800
                    ],
                ],
            ],
            'labels' => ['Hadir', 'Terlambat', 'Sakit', 'Izin', 'Alpa'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
        ];
    }
}
