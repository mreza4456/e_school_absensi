<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AdminStaffSekolahAnalisisAbsensi extends ChartWidget
{
    protected static ?string $heading = 'Analisis Absensi';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

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
        return [
            'datasets' => [
                [
                    'label' => 'Persentase Kehadiran',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        ];

    }

    protected function getType(): string
    {
        return 'line';
    }
}
