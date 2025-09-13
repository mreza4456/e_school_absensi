<?php

namespace App\Filament\Widgets;

use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;

class SuperAdminStaffUserChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Members by Role';

    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $organizationMembers = User::whereNotNull('organization_id')->count();
        $vendorMembers = User::whereNotNull('vendor_id')->count();
        $superAdmins = User::whereNull('organization_id')->whereNull('vendor_id')->count();

        return [
            'datasets' => [
                [
                    'data' => [$organizationMembers, $vendorMembers, $superAdmins],
                    'backgroundColor' => [
                        'rgba(16, 185, 129, 0.5)', // Emerald
                        'rgba(245, 158, 11, 0.5)', // Amber
                        'rgba(59, 130, 246, 0.5)', // Blue
                    ],
                ],
            ],
            'labels' => ['Organization Members', 'Vendor Members', 'Super Admin'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
