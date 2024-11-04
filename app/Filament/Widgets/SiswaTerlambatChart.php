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

        // Mapping English day names to Indonesian
        $dayMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        // Get active school days and corresponding entry times
        $activeDays = JadwalHarian::where('sekolah_id', $sekolahId)
            ->where('is_libur', false)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->get(['hari', 'jam_masuk', 'jam_masuk_selesai']);

        // Get the start and end of the current week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Calculate lateness count per day
        $dailyLateCount = $activeDays->map(function($jadwal) use ($sekolahId, $startOfWeek, $endOfWeek, $dayMapping) {
            // Convert Indonesian day name to English if necessary
            $dbDayName = array_search($jadwal->hari, $dayMapping);

            $lateCount = Absensi::where('sekolah_id', $sekolahId)
                ->where('keterangan', 'Terlambat')
                ->whereBetween(DB::raw("DATE(tanggal)"), [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->whereRaw("DAYNAME(tanggal) = ?", [$dbDayName])
                ->count();

            return [
                'hari' => $jadwal->hari,
                'count' => $lateCount,
            ];
        });

        // Debug output to ensure data is as expected
        // Uncomment for debugging: dd($dailyLateCount->toArray());

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa Terlambat',
                    'data' => $dailyLateCount->pluck('count')->toArray(),
                    'fill' => true,
                    'borderColor' => 'rgb(245, 158, 11)', // Amber-500
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)', // Amber-500 with opacity
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
