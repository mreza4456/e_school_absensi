<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Absensi;
use App\Models\Group;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassAttendanceBreakdownWidget extends ChartWidget
{
    protected static ?string $heading = 'Rincian Kehadiran Groups';

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

        $attendanceData = Absensi::join('members', 'absensis.uid', '=', 'members.uid')
            ->join('groups', 'members.group_id', '=', 'groups.id')
            ->where('members.sekolah_id', $sekolahId)
            ->select('groups.nama as class_name', 'absensis.keterangan', DB::raw('COUNT(*) as count'))
            ->groupBy('groups.id', 'groups.nama', 'absensis.keterangan')
            ->orderBy('groups.id')
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
                case 'Present':
                    $present[$record->class_name] = $record->count;
                    break;
                case 'Late':
                    $late[$record->class_name] = $record->count;
                    break;
                case 'Absent':
                    $absent[$record->class_name] = $record->count;
                    break;
            }
        }

        return [
            'labels' => array_values($classes),
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => array_values($present),
                    'backgroundColor' => 'rgba(34, 197, 94, 0.5)',
                ],
                [
                    'label' => 'Late',
                    'data' => array_values($late),
                    'backgroundColor' => 'rgba(245, 158, 11, 0.5)',
                ],
                [
                    'label' => 'Absent',
                    'data' => array_values($absent),
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
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
