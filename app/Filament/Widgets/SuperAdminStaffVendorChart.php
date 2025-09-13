<?php

namespace App\Filament\Widgets;

use App\Models\Vendor;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SuperAdminStaffVendorChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Vendor';

    protected static ?int $sort = 3;

    public ?string $filter = null;

    public function mount(): void
    {
        $this->filter = (string) Carbon::now()->year;
    }

    protected function getFilters(): ?array
    {
        $currentYear = Carbon::now()->year;

        return [
            $currentYear - 2 => ($currentYear - 2) . '',
            $currentYear - 1 => ($currentYear - 1) . '',
            $currentYear => $currentYear,
        ];

    }

    protected function getData(): array
    {
        $year = $this->filter ?: Carbon::now()->year;

        return [
            'datasets' => [
                [
                    'label' => "Vendor Dibuat ($year)",
                    'data' => collect(range(1, 12))->map(fn($month) =>
                        Vendor::whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->count()
                    )->toArray(),
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
