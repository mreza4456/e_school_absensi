<?php

namespace App\Filament\Widgets;

use App\Models\Mesin;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AdminStaffOrganizationMesinChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Machines';

    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        // roles baru
        return $user->hasRole(['admin_organization', 'staff_organization']);
    }

    protected function getData(): array
    {
        $organizationId = Auth::user()->organization_id;

        $mesinActive = Mesin::where('organization_id', $organizationId)
            ->where('status', true)
            ->count();

        $mesinInactive = Mesin::where('organization_id', $organizationId)
            ->where('status', false)
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Machines Status',
                    'data' => [$mesinActive, $mesinInactive],
                    'backgroundColor' => [
                        'rgba(14, 165, 233, 0.7)', // Active (sky-500)
                        'rgba(239, 68, 68, 0.7)',  // Inactive (red-500)
                    ],
                ],
            ],
            'labels' => ['Active', 'Inactive'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
