<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use App\Models\JadwalHarian;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminStaffSekolahSiswaTerlambat extends ChartWidget
{
    use HasWidgetShield;
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Siswa Terlambat';

    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        if (request()->is('admin')) {
            return false;
        }
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasRole(['admin_sekolah', 'staff_sekolah']);
    }

    protected function getData(): array
    {
        $sekolahId = Auth::user()->sekolah_id;

        // Day Mapping
        $dayMapping = [
            'Senin' => 'Monday',
            'Selasa' => 'Tuesday',
            'Rabu' => 'Wednesday',
            'Kamis' => 'Thursday',
            'Jumat' => 'Friday',
            'Sabtu' => 'Saturday',
            'Minggu' => 'Sunday',
        ];

        // Ambil Hari Aktif
        $activeDays = JadwalHarian::where('sekolah_id', $sekolahId)
            ->where('is_libur', false)
            ->orderByRaw("
                CASE
                    WHEN hari = 'Senin' THEN 1
                    WHEN hari = 'Selasa' THEN 2
                    WHEN hari = 'Rabu' THEN 3
                    WHEN hari = 'Kamis' THEN 4
                    WHEN hari = 'Jumat' THEN 5
                    WHEN hari = 'Sabtu' THEN 6
                    WHEN hari = 'Minggu' THEN 7
                END
            ")
            ->get(['hari', 'jam_masuk', 'jam_masuk_selesai']);

        // Ambil Rentang Tanggal dari Filter atau Default ke Minggu Ini
        [$startDate, $endDate] = $this->getDateRange();

        $dailyLateCount = $activeDays->map(function ($jadwal) use ($sekolahId, $startDate, $endDate, $dayMapping) {
            $englishDayName = $dayMapping[$jadwal->hari] ?? null;

            if (!$englishDayName) {
                return [
                    'hari' => $jadwal->hari,
                    'count' => 0,
                ];
            }

            $lateCount = Absensi::where('sekolah_id', $sekolahId)
                ->where('keterangan', 'Terlambat')
                ->whereBetween(DB::raw("tanggal::date"), [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->whereRaw("trim(to_char(tanggal, 'Day')) = ?", [trim($englishDayName)])
                ->count();

            return [
                'hari' => $jadwal->hari,
                'count' => $lateCount,
            ];
        });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa Terlambat',
                    'data' => $dailyLateCount->pluck('count')->toArray(),
                    'fill' => true,
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $dailyLateCount->pluck('hari')->toArray(),
        ];
    }

    protected function getDateRange(): array
    {
        $dateRange = $this->filters['dateRange'] ?? 'minggu_ini';

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
                $now->copy()->startOfWeek(),
                $now->copy()->endOfWeek(),
            ],
        };
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }

    // public function getDescription(): ?string
    // {
    //     $startDate = $this->filters['startDate'] ?? Carbon::now()->startOfWeek();
    //     $endDate = $this->filters['endDate'] ?? Carbon::now()->endOfWeek();

    //     return "Data dari " . Carbon::parse($startDate)->format('d M Y') .
    //            " sampai " . Carbon::parse($endDate)->format('d M Y');
    // }
}
