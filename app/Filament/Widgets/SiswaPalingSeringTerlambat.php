<?php

namespace App\Filament\Widgets;

use App\Models\Absen;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SiswaPalingSeringTerlambat extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);
        return $user->hasAnyRole(['admin_sekolah', 'staff_sekolah']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Siswa::query()
                    ->where('sekolah_id', Auth::user()->sekolah_id)
                    ->withCount(['absensi' => function (Builder $query) {
                        $query->where('keterangan', 'Terlambat');
                    }])
                    ->having('absensi_count', '>', 0) // Add this line to filter out students with zero late counts
                    ->orderByDesc('absensi_count')
                    ->limit(10);
            })
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama Siswa')
                    ->url(fn ($record) => route('filament.admin.resources.siswas.view', ['record' => $record->id]))
                    ->icon('heroicon-m-academic-cap')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Klik untuk melihat detail siswa'),
                Tables\Columns\TextColumn::make('panggilan')->label('Panggilan'),
                Tables\Columns\TextColumn::make('absensi_count')->label('Total Terlambat'),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Kelas')
                    ->placeholder('Semua Kelas')
                    ->options(function () {
                        return Kelas::where('sekolah_id', Auth::user()->sekolah_id)
                            ->pluck('nama', 'id')
                            ->toArray();
                    })
                    ->searchable(),
            ]);
    }
}
