<?php

namespace App\Filament\Widgets;

use App\Models\Groups;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AdminStaffOrganizationMembersGroupsChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Members Distribution by Groups';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        return $user->hasRole(['admin_organization', 'staff_organization']);
    }

    public function getData(): array
    {
        $organizationId = Auth::user()->organization_id;

        // Ambil groups dengan relasi members
        $groups = Groups::where('organization_id', $organizationId)
            ->with('members')
            ->get();

        $maleCounts = $groups->map(fn($group) => $group->members->where('jk', 'L')->count());
        $femaleCounts = $groups->map(fn($group) => $group->members->where('jk', 'P')->count());

        return [
            'datasets' => [
                [
                    'label' => 'Male',
                    'data' => $maleCounts->toArray(),
                    'backgroundColor' => 'rgba(14, 165, 233, 0.6)', // Blue
                    'borderColor' => 'rgb(14, 165, 233)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Female',
                    'data' => $femaleCounts->toArray(),
                    'backgroundColor' => 'rgba(219, 39, 119, 0.6)', // Pink
                    'borderColor' => 'rgb(219, 39, 119)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $groups->pluck('groups_name')->toArray(), // gunakan field name
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
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'x' => [
                    'stacked' => false,
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'stacked' => false,
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
