<?php

namespace App\Filament\Widgets;

use App\Models\Absensi;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AdminStaffSekolahAbsensiChart extends ChartWidget
{
    use HasWidgetShield;

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

    protected function getData(): array
    {
        $sekolahId = Auth::user()->sekolah_id;

        $absensi = Absensi::where('sekolah_id', $sekolahId);
        $hadir = (clone $absensi)->where('keterangan', 'Masuk')->count();
        $terlambat = (clone $absensi)->where('keterangan', 'Terlambat')->count();
        $sakit = (clone $absensi)->where('keterangan', 'Sakit')->count();
        $izin = (clone $absensi)->where('keterangan', 'Izin')->count();
        $alpa = (clone $absensi)->where('keterangan', '')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status Kehadiran Siswa',
                    'data' => [$hadir, $sakit, $izin, $alpa],
                    'backgroundColor' => [
                        'rgba(251, 146, 60, 0.7)',  // Orange-400
                        'rgba(249, 115, 22, 0.7)',  // Orange-500
                        'rgba(234, 88, 12, 0.7)',   // Orange-600
                        'rgba(194, 65, 12, 0.7)',   // Orange-700
                    ],
                ],
            ],
            'labels' => ['Hadir', 'Sakit', 'Izin', 'Alpa'],
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
