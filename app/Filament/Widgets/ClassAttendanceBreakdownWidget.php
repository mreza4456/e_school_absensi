<?php

namespace App\Filament\Widgets;

use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Kelas;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassAttendanceBreakdownWidget extends ChartWidget
{
    protected static ?string $heading = 'Rincian Kehadiran Kelas';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasAnyRole(['admin_sekolah', 'staff_sekolah']);
    }

    protected function getData(): array
    {
        $sekolahId = Auth::user()->sekolah_id;

        // Ubah query untuk menggunakan relasi yang benar
        $attendanceData = Absensi::join('siswas', 'absensis.uid', '=', 'siswas.uid')
            ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->where('siswas.sekolah_id', $sekolahId)
            ->select('kelas.nama as class_name', 'absensis.keterangan', DB::raw('COUNT(*) as count'))
            ->groupBy('kelas.id', 'kelas.nama', 'absensis.keterangan')
            ->orderBy('kelas.id')
            ->get();

        $classes = [];
        $present = [];
        $late = [];
        $absent = [];

        foreach ($attendanceData as $record) {
            if (!isset($classes[$record->class_name])) {
                $classes[$record->class_name] = $record->class_name;
                $present[$record->class_name] = 0;
                $late[$record->class_name] = 0;
                $absent[$record->class_name] = 0;
            }

            switch ($record->keterangan) {
                case 'Masuk':
                    $present[$record->class_name] = $record->count;
                    break;
                case 'Terlambat':
                    $late[$record->class_name] = $record->count;
                    break;
                case 'Belum Absen':
                    $absent[$record->class_name] = $record->count;
                    break;
            }
        }

        return [
            'labels' => array_values($classes),
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => array_values($present),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)', // Hijau
                ],
                [
                    'label' => 'Terlambat',
                    'data' => array_values($late),
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)', // Amber
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => array_values($absent),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)', // Merah
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
