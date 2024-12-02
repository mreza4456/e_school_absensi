<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class AdminStaffSekolahAbsensiOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getDateRange(): array
    {
        $dateRange = $this->filters['dateRange'] ?? '7_hari_terakhir';

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

    public static function canView(): bool
    {
        if (request()->is('admin')) {
            return false;
        }
        return true;
    }

    protected function getStats(): array
    {
        [$startDate, $endDate] = $this->getDateRange();
        $kelas_id = $this->filters['kelas'] ?? null;

        $sekolah_id = Auth::user()->sekolah_id ?? null;

        // Modify the siswa query to include class filter
        $siswaQuery = Siswa::where('sekolah_id', $sekolah_id);
        if ($kelas_id) {
            $siswaQuery->where('kelas_id', $kelas_id);
        }
        $siswa = $siswaQuery->count();

        // Base absensi query with date and school filters
        $absensi = Absensi::query()
            ->where('sekolah_id', $sekolah_id)
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Add class filter if selected
        if ($kelas_id) {
            $absensi->whereHas('siswa', function (Builder $query) use ($kelas_id) {
                $query->where('kelas_id', $kelas_id);
            });
        }

        $hadir = (clone $absensi)
            ->where(function($query) {
                $query->where('keterangan', 'Masuk')
                      ->orWhere('keterangan', 'Terlambat');
            })->count();

        $terlambat = (clone $absensi)
            ->where('keterangan', 'Terlambat')
            ->count();

        $alpa = (clone $absensi)
            ->where('keterangan', '')
            ->count();

        $sakit = (clone $absensi)
            ->where('keterangan', 'Sakit')
            ->count();

        $izin = (clone $absensi)
            ->where('keterangan', 'Izin')
            ->count();

        return [
            Stat::make('Hadir', $hadir)
                ->description("$hadir dari $siswa Siswa Hadir")
                ->descriptionIcon('heroicon-m-arrow-right-circle', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Sakit', $sakit)
                ->description("$sakit dari $siswa Siswa Sakit")
                ->descriptionIcon('heroicon-m-heart', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

            Stat::make('Izin', $izin)
                ->description("$izin dari $siswa Siswa Izin")
                ->descriptionIcon('heroicon-m-hand-raised', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Alpa', $alpa)
                ->description("$alpa dari $siswa Siswa Alpa")
                ->descriptionIcon('heroicon-m-x-circle', IconPosition::Before)
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
        ];
    }
}
