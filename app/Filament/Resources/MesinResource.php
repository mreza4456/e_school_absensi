<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MesinResource\Pages;
use App\Filament\Resources\MesinResource\RelationManagers;
use App\Models\Mesin;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MesinResource extends Resource
{
    protected static ?string $model = Mesin::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'Manajemen Vendor';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            return $user->hasRole('admin_sekolah') || $user->hasRole('staff_sekolah') ? 'Manajemen Mesin' : 'Manajemen Vendor';
        }
    }

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        return $form
            ->schema([
                Forms\Components\Section::make('Detail Mesin')
                ->description('Masukan data mesin')
                ->schema([
                    Forms\Components\Select::make('vendor_id')
                        ->required()
                        ->relationship(name: 'vendor', titleAttribute: 'nama')
                        ->hidden(fn () => !$user->hasRole('super_admin'))
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('sekolah_id')
                        ->nullable()
                        ->relationship(name: 'sekolah', titleAttribute: 'nama')
                        ->hidden(fn () => !$user->hasRole('super_admin'))
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('kode_mesin')
                        ->required()
                        ->unique(Mesin::class, 'kode_mesin', ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('idle')
                        ->nullable()
                        ->hidden(fn () => Auth::user()->vendor_id !== null)
                        ->numeric()
                        ->default(15),
                    Forms\Components\DatePicker::make('tgl_pembuatan')
                        ->default(Carbon::now()),
                    ])->columns(2),
                Forms\Components\Toggle::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vendor.nama')
                    ->label('Vendor')
                    ->url(fn ($record) => route('filament.admin.resources.vendors.view', ['record' => $record->vendor_id]))
                    ->hidden(fn () => !$user->hasRole('super_admin'))
                    ->icon('heroicon-m-building-office')
                    ->color('primary')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->tooltip('Klik untuk lihat detail vendor'),

                Tables\Columns\TextColumn::make('sekolah.nama')
                    ->label('Sekolah')
                    ->url(fn ($record) => $record->sekolah_id ? route('filament.admin.resources.sekolahs.view', ['record' => $record->sekolah_id]) : null)
                    ->hidden(fn () => !$user->hasRole('super_admin'))
                    ->icon('heroicon-m-building-library')
                    ->color('success')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->tooltip(fn ($record) => $record->sekolah_id ? 'Klik untuk lihat detail sekolah' : null),

                Tables\Columns\TextColumn::make('kode_mesin')
                    ->label('Kode Mesin')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('idle')
                    ->label('Idle')
                    ->hidden(fn () => $user->hasRole('vendor'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('wifi.ssid')
                    ->label('Wifi')
                    ->url(fn ($record) => route('filament.admin.resources.wifis.view', ['record' => $record->wifi->id]))
                    ->color('primary')
                    ->hidden(fn () => $user->hasRole('vendor'))
                    ->badge()
                    ->icon('heroicon-m-wifi'),

                Tables\Columns\TextColumn::make('tgl_pembuatan')
                    ->label('Tanggal Pembuatan')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->color(fn ($record): string => match ($record->keterangan) {
                        'Sudah Aktif' => 'success',
                        'Belum Diset' => 'primary',
                        'Tidak Aktif' => 'danger',
                        default => 'gray',
                    })
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->hidden(fn () => $user->hasRole('vendor')),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->boolean()
                    ->sortable()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->hidden(fn () => $user->hasRole('vendor')),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Tanggal Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort($user->hasRole('super_admin') ? 'vendor.nama' : 'created_at')
            ->filters([
                // Status Filters
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        true => 'Aktif',
                        false => 'Nonaktif',
                    ])
                    ->native(false),

                SelectFilter::make('keterangan')
                    ->label('Keterangan')
                    ->options([
                        'Sudah Aktif' => 'Sudah Aktif',
                        'Belum Diset' => 'Belum Diset',
                        'Tidak Aktif' => 'Tidak Aktif',
                    ])
                    ->native(false),

                // Relationship Filters
                SelectFilter::make('vendor')
                    ->relationship('vendor', 'nama')
                    ->label('Vendor')
                    ->searchable()
                    ->preload()
                    ->native(false),

                SelectFilter::make('sekolah')
                    ->relationship('sekolah', 'nama')
                    ->label('Sekolah')
                    ->searchable()
                    ->preload()
                    ->native(false),

                // Date Range Filters
                Filter::make('tgl_pembuatan')
                    ->form([
                        Forms\Components\DatePicker::make('dari_tanggal')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('sampai_tanggal')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tgl_pembuatan', '>=', $date)
                            )
                            ->when(
                                $data['sampai_tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tgl_pembuatan', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['dari_tanggal'] ?? null) {
                            $tanggalDari = Carbon::parse($data['dari_tanggal']);
                            $indicators['dari_tanggal'] = 'Dari ' . $tanggalDari->format('d/m/Y');
                        }

                        if ($data['sampai_tanggal'] ?? null) {
                            $tanggalSampai = Carbon::parse($data['sampai_tanggal']);
                            $indicators['sampai_tanggal'] = 'Sampai ' . $tanggalSampai->format('d/m/Y');
                        }

                        return $indicators;
                    })
                    ->columns(2)->columnSpan(2),

                // Main Data Filters
                Filter::make('kode_mesin')
                    ->form([
                        Forms\Components\TextInput::make('kode_mesin')
                            ->label('Kode Mesin')
                            ->placeholder('Cari kode mesin...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['kode_mesin'],
                            fn (Builder $query, $value): Builder => $query->where('kode_mesin', 'like', "%{$value}%")
                        );
                    }),

                // Creation Date Filter
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dibuat Dari'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Dibuat Sampai'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from'] ?? null) {
                            $createdFrom = Carbon::parse($data['created_from']);
                            $indicators['created_from'] = 'Dibuat dari ' . $createdFrom->format('d/m/Y');
                        }

                        if ($data['created_until'] ?? null) {
                            $createdUntil = Carbon::parse($data['created_until']);
                            $indicators['created_until'] = 'Dibuat sampai ' . $createdUntil->format('d/m/Y');
                        }

                        return $indicators;
                    })
                    ->columns(2)->columnSpan(2)
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMesins::route('/'),
            'create' => Pages\CreateMesin::route('/create'),
            'view' => Pages\ViewMesin::route('/{record}'),
            'edit' => Pages\EditMesin::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            if ($user && $user->hasRole('vendor')) {
                // If user is 'vendor', filter by their sekolah_id
                $query->where('vendor_id', $user->vendor_id);
            }
            elseif ($user && $user->hasRole('admin_sekolah') || $user->hasRole('staff_sekolah')) {
                // If user is 'admin' or 'sekolah', filter by their sekolah_id
                $query->where('sekolah_id', $user->sekolah_id);
            }
        }

        return $query;
    }
}
