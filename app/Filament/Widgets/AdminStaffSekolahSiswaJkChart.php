<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use App\Models\Siswa;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AdminStaffSekolahSiswaJkChart extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Jenis Kelamin Siswa';

    protected static ?int $sort = 4;

    public ?string $filter = 'all';

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasRole(['admin_sekolah', 'staff_sekolah']);
    }

    protected function getFilters(): ?array
    {
        return [
            'all' => 'Semua',
            ...Kelas::where('sekolah_id', Auth::user()->sekolah_id)
                ->pluck('nama_kelas', 'id')
                ->toArray(),
        ];
    }

    protected function getData(): array
    {
        $query = Siswa::query()
            ->where('sekolah_id', Auth::user()->sekolah_id);

        if ($this->filter && $this->filter !== 'all') {
            $query->where('kelas_id', $this->filter);
        }

        $laki = $query->clone()->where('jk', 'L')->count();
        $perempuan = $query->clone()->where('jk', 'P')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa per Jenis Kelamin',
                    'data' => [$laki, $perempuan],
                    'backgroundColor' => [
                        'rgba(14, 165, 233, 0.5)', // Biru (sky-500)
                        'rgba(219, 39, 119, 0.5)', // Pink yang lebih soft (pink-600)
                    ],
                ],
            ],
            'labels' => ['Laki-laki', 'Perempuan'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
