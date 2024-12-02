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

        $dayMapping = [
            'Senin' => 'Monday   ',
            'Selasa' => 'Tuesday  ',
            'Rabu' => 'Wednesday',
            'Kamis' => 'Thursday ',
            'Jumat' => 'Friday   ',
            'Sabtu' => 'Saturday ',
            'Minggu' => 'Sunday   ',
        ];

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

        // Get the date range from filters, default to current week if not set
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;

        $dailyLateCount = $activeDays->map(function($jadwal) use ($sekolahId, $startDate, $endDate, $dayMapping) {
            $englishDayName = $dayMapping[$jadwal->hari] ?? null;

            if (!$englishDayName) {
                return [
                    'hari' => $jadwal->hari,
                    'count' => 0,
                ];
            }

            $lateCount = Absensi::where('sekolah_id', $sekolahId)
                ->where('keterangan', 'Terlambat')
                ->whereBetween(DB::raw("tanggal::date"), [$startDate, $endDate])
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
