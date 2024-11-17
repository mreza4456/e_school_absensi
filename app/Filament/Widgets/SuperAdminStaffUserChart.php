<?php

namespace App\Filament\Widgets;

use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;

class SuperAdminStaffUserChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Pengguna';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $schoolUsers = User::whereNotNull('sekolah_id')->count();
        $vendorUsers = User::whereNotNull('vendor_id')->count();
        $adminUsers = User::whereNull('sekolah_id')->whereNull('vendor_id')->count();

        return [
            'datasets' => [
                [
                    'data' => [$schoolUsers, $vendorUsers, $adminUsers],
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.5)',  // #10B981 dengan opacity 0.5
                        'rgba(245, 158, 11, 0.5)',  // #F59E0B dengan opacity 0.5
                        'rgba(59, 130, 246, 0.5)',  // #3B82F6 dengan opacity 0.5
                    ],
                ],
            ],
            'labels' => ['Pengguna Sekolah', 'Pengguna Vendor', 'Admin'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
