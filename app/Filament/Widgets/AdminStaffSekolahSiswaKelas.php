<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class AdminStaffSekolahSiswaKelas extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Distribusi Siswa per Kelas';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasRole(['admin_sekolah', 'staff_sekolah']);
    }

    public function getData(): array
    {
        $sekolah_id = Auth::user()->sekolah_id;
        $kelas = Kelas::where('sekolah_id', $sekolah_id)->with('siswa')->get();

        $jumlahLakiLaki = $kelas->map(function ($kelas) {
            return $kelas->siswa->where('jk', 'L')->count();
        });

        $jumlahPerempuan = $kelas->map(function ($kelas) {
            return $kelas->siswa->where('jk', 'P')->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Laki-laki',
                    'data' => $jumlahLakiLaki->toArray(),
                    'backgroundColor' => 'rgba(14, 165, 233, 0.5)', // Biru (sky-500)
                    'borderColor' => 'rgb(14, 165, 233)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Perempuan',
                    'data' => $jumlahPerempuan->toArray(),
                    'backgroundColor' => 'rgba(219, 39, 119, 0.5)', // Pink (pink-600)
                    'borderColor' => 'rgb(219, 39, 119)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $kelas->pluck('nama_kelas')->toArray(),
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

    // public function getDescription(): string|Htmlable|null
    // {
    //     return 'Jumlah Siswa Perkelas';
    // }
}
