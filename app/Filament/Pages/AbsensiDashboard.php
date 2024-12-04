<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AdminStaffSekolahAbsensiChart;
use App\Filament\Widgets\AdminStaffSekolahAbsensiOverview;
use App\Filament\Widgets\AdminStaffSekolahAnalisisAbsensi;
use App\Filament\Widgets\AdminStaffSekolahSiswaSeringTerlambat;
use App\Filament\Widgets\AdminStaffSekolahSiswaTerlambat;
use App\Models\Kelas;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class AbsensiDashboard extends Page
{
    use HasFiltersForm;

    public $dateRange = '7_hari_terakhir';
    public $kelas;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.absensi-dashboard';
    protected static ?string $title = 'Dasbor Absensi';

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('dateRange')
                    ->options([
                        'hari_ini' => 'Hari Ini',
                        'kemarin' => 'Kemarin',
                        '7_hari_terakhir' => '7 Hari Terakhir',
                        '30_hari_terakhir' => '30 Hari Terakhir',
                        'bulan_ini' => 'Bulan Ini',
                        'bulan_lalu' => 'Bulan Lalu',
                    ])
                    ->default('hari_ini')
                    ->native(false)
                    ->live(),
                Select::make('kelas')
                    ->options(function() {
                        $sekolah_id = Auth::user()->sekolah_id;
                        return Kelas::where('sekolah_id', $sekolah_id)
                            ->pluck('nama_kelas', 'id');
                    })
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->label('Kelas')
                    ->placeholder('Pilih Kelas'),
            ]);
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasRole(['admin_sekolah', 'staff_sekolah']);
    }
}
