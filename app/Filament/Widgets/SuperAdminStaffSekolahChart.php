<?php

namespace App\Filament\Widgets;

use App\Models\Sekolah;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class SuperAdminStaffSekolahChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Sekolah';

    protected static ?int $sort = 2;

    public ?string $filter = null;

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
                    'label' => "Sekolah Dibuat ($year)",
                    'data' => collect(range(1, 12))->map(fn($month) =>
                        Sekolah::whereMonth('created_at', $month)
                            ->whereYear('created_at', $year)
                            ->count()
                    )->toArray(),
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
