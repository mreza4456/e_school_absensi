<?php

namespace App\Filament\Widgets;

use App\Models\Groups;
use App\Models\Members;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AdminStaffOrganizationMembersGenderChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Members by Gender';

    protected static ?int $sort = 4;

    public ?string $filter = 'all';

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        // pakai role baru
        return $user->hasRole(['admin_organization', 'staff_organization']);
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'All',
            ...Groups::where('organization_id', Auth::user()->organization_id)
                ->pluck('groups_name', 'id') // pakai kolom name (bukan Groups_name)
                ->toArray(),
        ];
    }

    protected function getData(): array
    {
        $query = Members::query()
            ->where('organization_id', Auth::user()->organization_id);

        if ($this->filter && $this->filter !== 'all') {
            $query->where('group_id', $this->filter);
        }

        // hitung gender
        $male = (clone $query)->where('jk', 'L')->count();
        $female = (clone $query)->where('jk', 'P')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Members Gender Distribution',
                    'data' => [$male, $female],
                    'backgroundColor' => [
                        'rgba(14, 165, 233, 0.6)', // Biru (Male)
                        'rgba(219, 39, 119, 0.6)', // Pink (Female)
                    ],
                ],
            ],
            'labels' => ['Male', 'Female'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
