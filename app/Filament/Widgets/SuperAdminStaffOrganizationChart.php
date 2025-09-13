<?php

namespace App\Filament\Widgets;

use App\Models\Organization;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SuperAdminStaffOrganizationsChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Organizations';

    protected static ?int $sort = 2;

    public ?string $filter = null;

    protected function getFilters(): ?array
    {
        $currentYear = Carbon::now()->year;
        return [
            $currentYear - 2 => (string) ($currentYear - 2),
            $currentYear - 1 => (string) ($currentYear - 1),
            $currentYear => (string) $currentYear,
        ];
    }

    protected function getData(): array
    {
        $year = $this->filter ?: Carbon::now()->year;

        return [
            'datasets' => [
                [
                    'label' => "Organizations Created ($year)",
                    'data' => collect(range(1, 12))->map(fn ($month) =>
                        Organization::whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->count()
                    )->toArray(),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)', // biru lembut
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
