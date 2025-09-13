<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Models\Absensi;
use App\Models\Groups;
use App\Models\Organization;
use App\Models\Members;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function getPluralLabel(): string
{
    return __('Attendance');
}

    public static function getNavigationGroup(): ?string
{
    return __('Organization');
}

public static function getNavigationLabel(): string
{
    return __('Attendance');
}

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Attendance Detail'))
                    ->description(__('Enter members attendance data'))
                    ->schema([
                        Forms\Components\Select::make('organization_id')
                            ->required()
                            ->label(__('message.Organization'))
                            ->relationship('organization', 'nama')
                            ->searchable()
                            ->default(fn() => Auth::user()->organization_id ?? null)
                            ->hidden(fn() => Auth::user()->organization_id !== null)
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $set('groups_filter', null);
                                $set('members_id', null);
                                $set('tanggal', null);
                                $set('waktu', null);
                                $set('keterangan', null);

                                if (!$organizationId = $get('organization_id')) {
                                    return;
                                }

                                $timezone = cache()->remember(
                                    "organization_timezone_{$organizationId}",
                                    now()->addMinutes(30),
                                    fn() => Organization::find($organizationId)?->timezone
                                );

                                if ($timezone) {
                                    $timezone = match ($timezone) {
                                        'WIB' => 'Asia/Jakarta',
                                        'WITA' => 'Asia/Makassar',
                                        'WIT' => 'Asia/Jayapura',
                                        default => 'Asia/Jakarta'
                                    };

                                    $now = now()->timezone($timezone);
                                    $set('tanggal', $now->format('Y-m-d'));
                                    $set('waktu', $now->format('H:i'));
                                }
                            }),

                        Forms\Components\Select::make('groups_filter')
                            ->label(__('Groups'))
                            ->options(fn(Get $get): Collection =>
                                $get('organization_id')
                                    ? Groups::where('organization_id', $get('organization_id'))->pluck('groups_name', 'id')
                                    : collect()
                            )
                            ->searchable()
                            ->live()
                            ->afterStateHydrated(function ($state, callable $set) {
                                if (Route::currentRouteName() === 'filament.admin.resources.absensis.edit' || Route::currentRouteName() === 'filament.admin.resources.absensis.view') {
                                    $id = Request::route('record');
                                    $absensi = Absensi::find($id);
                                    $set('groups_filter', $absensi->members->groups_id ?? null);
                                }
                            })
                            ->afterStateUpdated(function (callable $set) {
                                $set('members_id', null);
                            }),

                        Forms\Components\Select::make('members_id')
                            ->required()
                            ->label(__('Members'))
                            ->options(fn(Get $get): Collection =>
                                $get('organization_id')
                                    ? cache()->remember(
                                        "members_options_{$get('organization_id')}_" .
                                        ($get('groups_filter') ? "groups_{$get('groups_filter')}" : 'all'),
                                        now()->addMinutes(5),
                                        fn() => Members::query()
                                            ->where('organization_id', $get('organization_id'))
                                            ->when($get('groups_filter'), fn($query) => $query->where('groups_id', $get('groups_filter')))
                                            ->get()
                                            ->mapWithKeys(fn($members) => [
                                                $members->id => "{$members->nama} - {$members->groups->groups_name}"
                                            ])
                                    )
                                    : collect()
                            )
                            ->searchable()
                            ->preload()
                            ->disabled(fn(Get $get): bool => !$get('organization_id')),

                        Forms\Components\DatePicker::make('tanggal')
                            ->required()
                            ->label(__('Date'))
                            ->default(fn() => now()->format('Y-m-d'))
                            ->live()
                            ->afterStateUpdated(function (Get $get, callable $set, $state) {
                                self::setKeteranganAutomatically($get, $set, $state, null);
                            }),

                        Forms\Components\TimePicker::make('waktu')
                            ->required()
                            ->label(__('Time'))
                            ->default(fn() => now()->format('H:i'))
                            ->live()
                            ->afterStateUpdated(function (Get $get, callable $set, $state) {
                                self::setKeteranganAutomatically($get, $set, null, $state);
                            }),

                        Forms\Components\Select::make('keterangan')
                            ->label(__('Information'))
                            ->options([
                                'Masuk' => __('Present'),
                                'Terlambat' => __('Late'),
                                'Pulang' => __('Go Home'),
                                'Sakit' => __('Sick'),
                                'Izin' => __('Permit'),
                                'Alpa' => __('Alpha')
                            ])
                            ->nullable()
                            ->native(false)
                    ])->columns(2)
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label(__('ID'))->searchable()->toggleable(true),
                Tables\Columns\TextColumn::make('tanggal')->label(__('Date'))->sortable()->searchable()
                    ->getStateUsing(fn($record) => Carbon::parse($record->tanggal)->translatedFormat('d M Y')),
                Tables\Columns\TextColumn::make('waktu')->label(__('Time'))->sortable()->searchable()
                    ->getStateUsing(fn($record) => Carbon::parse($record->waktu)->format('H:i')),
                Tables\Columns\TextColumn::make('organization.nama')
                    ->label(__('Organization'))
                    ->hidden(fn() => Auth::user()->organization_id != null)
                    ->icon('heroicon-m-building-library')->color('success')->badge()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('members.nama')
                    ->label(__('Members'))
                    ->icon('heroicon-m-academic-cap')->color('primary')->badge()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('members.groups.groups_name')
                    ->label(__('Groups'))
                    ->icon('heroicon-m-user-group')->color('primary')->badge()->sortable()->searchable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label(__('Information'))
                    ->color(fn($record): string => match ($record->keterangan) {
                        'Masuk', 'Pulang' => 'success',
                        'Terlambat' => 'danger',
                        'Izin' => 'warning',
                        'Sakit' => 'info',
                        'Alpa' => 'gray',
                        default => 'gray',
                    })
                    ->icon(fn($record): string => match ($record->keterangan) {
                        'Masuk' => 'heroicon-m-arrow-right-circle',
                        'Pulang' => 'heroicon-m-arrow-left-circle',
                        'Terlambat' => 'heroicon-m-clock',
                        'Izin' => 'heroicon-m-hand-raised',
                        'Sakit' => 'heroicon-m-heart',
                        'Alpa' => 'heroicon-m-x-circle',
                        default => 'heroicon-m-question-mark-circle',
                    })
                    ->badge()->searchable()->sortable(),
                Tables\Columns\TextColumn::make('uid')->label(__('Attendance Code'))->sortable()->searchable()->toggleable(true),
                Tables\Columns\TextColumn::make('created_at')->label(__('Created At'))->dateTime()->sortable()->toggleable(true),
                Tables\Columns\TextColumn::make('updated_at')->label(__('Updated At'))->dateTime()->sortable()->toggleable(true),
            ])
            ->defaultSort('tanggal')
            ->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAbsensis::route('/'),
            'create' => Pages\CreateAbsensi::route('/create'),
            'view' => Pages\ViewAbsensi::route('/{record}'),
            'edit' => Pages\EditAbsensi::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            if ($user->hasRole('admin_organization') || $user->hasRole('organization')) {
                $query->where('organization_id', $user->organization_id);
            }
        }

        return $query;
    }
}
