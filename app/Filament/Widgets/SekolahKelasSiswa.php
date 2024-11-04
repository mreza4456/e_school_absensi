<?php

namespace App\Filament\Widgets;

use App\Models\Kelas;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class SekolahKelasSiswa extends ChartWidget
{
    protected static ?string $heading = 'Kelas';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasAnyRole(['admin_sekolah', 'staff_sekolah']);
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
            'labels' => $kelas->pluck('nama')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
