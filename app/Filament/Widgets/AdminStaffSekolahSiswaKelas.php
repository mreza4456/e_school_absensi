<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AdminStaffSekolahSiswaKelas extends ChartWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Kelas';

    protected static ?int $sort = 2;

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

        $jumlahSiswaPerKelas = $kelas->map(function ($kelas) {
            return $kelas->siswa->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Siswa per Kelas',
                    'data' => $jumlahSiswaPerKelas->toArray(),
                ],
            ],
            'labels' => $kelas->pluck('nama_kelas')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
