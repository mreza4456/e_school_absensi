<?php

namespace App\Filament\Widgets;

use App\Models\Mesin;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class MesinSekolah extends ChartWidget
{
    protected static ?string $heading = 'Mesin Sekolah';

    protected static ?int $sort = 5;

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasAnyRole(['admin_sekolah', 'staff_sekolah']);
    }

    protected function getData(): array
    {
        $mesinActive = Mesin::where('sekolah_id', Auth::user()->sekolah_id)->where('status', true)->count();
        $mesinInactive = Mesin::where('sekolah_id', Auth::user()->sekolah_id)->where('status', false)->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa per Jenis Kelamin',
                    'data' => [$mesinActive, $mesinInactive],
                    'backgroundColor' => [
                        'rgba(14, 165, 233, 0.5)', // Biru (sky-500)
                        'rgba(219, 39, 119, 0.5)', // Pink yang lebih soft (pink-600)
                    ],
                ],
            ],
            'labels' => ['Aktif', 'Tidak Aktif'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
