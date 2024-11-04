<?php

namespace App\Filament\Widgets;

use App\Models\Vendor;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class VendorChart extends ChartWidget
{
    protected static ?string $heading = 'Vendor';

    protected static ?int $sort = 3;

    public ?string $filter = null;

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasAnyRole(['super_admin', 'staff']);
    }

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
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
