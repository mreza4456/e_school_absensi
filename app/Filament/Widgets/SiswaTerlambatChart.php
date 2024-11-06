<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use App\Models\JadwalHarian;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaTerlambatChart extends ChartWidget
{
    protected static ?string $heading = 'Siswa Terlambat Minggu ini';
    protected static ?int $sort = 3;

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasAnyRole(['admin_sekolah', 'staff_sekolah']);
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

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $dailyLateCount = $activeDays->map(function($jadwal) use ($sekolahId, $startOfWeek, $endOfWeek, $dayMapping) {
            $englishDayName = $dayMapping[$jadwal->hari] ?? null;

            if (!$englishDayName) {
                return [
                    'hari' => $jadwal->hari,
                    'count' => 0,
                ];
            }

            $lateCount = Absensi::where('sekolah_id', $sekolahId)
                ->where('keterangan', 'Terlambat')
                ->whereBetween(DB::raw("tanggal::date"), [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
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
        return 'line';
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
}
