<?php

namespace App\Filament\Widgets;

use App\Models\Mesin;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class SuperAdminStaffMesinTable extends BaseWidget
{
    use HasWidgetShield;

    protected static ?string $heading = 'Mesin yang perlu Diset';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Mesin::query()->with(['sekolah', 'vendor'])->where('keterangan', 'Belum Diset')
            )
            ->columns([
                Tables\Columns\TextColumn::make('kode_mesin')
                    ->url(fn ($record) => route('filament.admin.resources.mesins.view', ['record' => $record->id]))
                    ->icon('heroicon-m-cpu-chip')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->tooltip('Klik untuk melihat detail mesin'),
                TextColumn::make('vendor.nama')
                    ->label('Vendor')
                    ->url(fn ($record) => route('filament.admin.resources.vendors.view', ['record' => $record->vendor_id]))
                    ->icon('heroicon-m-building-office')
                    ->color('primary')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->tooltip('Klik untuk lihat detail vendor'),

                TextColumn::make('sekolah.nama')
                    ->label('Sekolah'),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->color(fn ($record): string => match ($record->keterangan) {
                        'Sudah Aktif' => 'success',
                        'Belum Diset' => 'primary',
                        'Tidak Aktif' => 'danger',
                        default => 'gray',
                    })
                    ->badge()
                    ->sortable()
                    ->searchable(),

                IconColumn::make('status')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('vendor')
                    ->relationship('vendor', 'nama')
                    ->label('Vendor')
                    ->placeholder('Semua Vendor')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('sekolah')
                    ->relationship('sekolah', 'nama')
                    ->label('Sekolah')
                    ->placeholder('Semua Sekolah')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('keterangan')
                    ->options([
                        'Sudah Aktif' => 'Sudah Aktif',
                        'Belum Diset' => 'Belum Diset',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ])
                    ->label('Status Aktivasi')
                    ->native(false)
                    ->placeholder('Semua Status'),

                TernaryFilter::make('status')
                    ->label('Status Mesin')
                    ->placeholder('Semua Status')
                    ->trueLabel('Aktif')
                    ->native(false)
                    ->falseLabel('Tidak Aktif'),
            ], layout: FiltersLayout::Dropdown);
    }
}
