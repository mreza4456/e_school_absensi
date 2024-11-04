<?php

namespace App\Filament\Widgets;

use App\Models\Sekolah;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SekolahChart extends ChartWidget
{
    protected static ?string $heading = 'Sekolah';

    protected static ?int $sort = 2;

    public ?string $filter = null;

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasAnyRole(['super_admin', 'staff']);
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
