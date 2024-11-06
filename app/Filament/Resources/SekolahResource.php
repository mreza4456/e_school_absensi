<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SekolahResource\Pages;
use App\Filament\Resources\SekolahResource\RelationManagers;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Sekolah;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SekolahResource extends Resource
{
    protected static ?string $model = Sekolah::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Manajemen Sekolah';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('School Information')
                    ->description('Enter School Information')
                    ->schema([
                        Forms\Components\FileUpload::make('logo')
                            ->image()
                            ->avatar()
                            ->directory('logos')
                            ->visibility('public')
                            ->columnSpanFull()
                            ->imageEditor()
                            ->circleCropper()
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('400'),
                        Forms\Components\TextInput::make('npsn')
                            ->required()
                            ->numeric()
                            ->unique(Sekolah::class, 'npsn', ignoreRecord: true),
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('jenjang')
                            ->required()
                            ->options([
                                'TK' => 'TK',
                                'SD' => 'SD',
                                'SMP' => 'SMP',
                                'SMA' => 'SMA',
                                'SMK' => 'SMK',
                            ])
                            ->native(false),
                        Forms\Components\TextInput::make('nik_kepala')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nama_kepala')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Address and Contact')
                    ->description('Enter Address and Contact')
                    ->schema([
                        Forms\Components\Textarea::make('alamat')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Select::make('provinsi_code')
                            ->required()
                            ->relationship(name: 'provinsi', titleAttribute: 'nama')
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (callable $set) {
                                $set('kota_code', null);
                            })
                            ->columnSpanFull(),
                        Forms\Components\Select::make('kota_code')
                            ->label('Kabupaten/Kota')
                            ->required()
                            ->options(fn (Get $get): Collection => Kota::query()
                                ->where('provinsi_code', $get('provinsi_code'))
                                ->get()
                                ->pluck('nama', 'code'))
                            ->searchable()
                            ->preload()
                            ->afterStateUpdated(function (callable $set) {
                                $set('kecamatan_code', null);
                            })
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('no_telp')
                            ->tel()
                            ->required()
                            ->unique(Sekolah::class, 'no_telp', ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(Sekolah::class, 'email', ignoreRecord: true)
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Details')
                    ->description('Enter Details')
                    ->schema([
                        Forms\Components\Select::make('timezone')
                            ->required()
                            ->options([
                                'WIT' => 'WIT',
                                'WITA' => 'WITA',
                                'WIB' => 'WIB',
                            ])
                            ->native(false)
                            ->columnSpanFull(),
                        Forms\Components\Section::make('Jadwal Operasional')
                            ->description('Atur jadwal operasional sekolah per hari')
                            ->schema([
                                Forms\Components\Repeater::make('jadwalHarian')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('hari')
                                            ->options([
                                                'Senin' => 'Senin',
                                                'Selasa' => 'Selasa',
                                                'Rabu' => 'Rabu',
                                                'Kamis' => 'Kamis',
                                                'Jumat' => 'Jumat',
                                                'Sabtu' => 'Sabtu',
                                                'Minggu' => 'Minggu',
                                            ])
                                            ->required()
                                            ->native(false),
                                        Forms\Components\Toggle::make('is_libur')
                                            ->label('Hari Libur')
                                            ->default(false)
                                            ->live(),
                                        Forms\Components\Grid::make(3)
                                            ->schema([
                                                Forms\Components\Group::make([
                                                    Forms\Components\TimePicker::make('jam_masuk')
                                                        ->label('Jam Masuk')
                                                        ->required(fn (Get $get): bool => ! $get('is_libur'))
                                                        ->nullable()
                                                        ->visible(fn (Get $get): bool => ! $get('is_libur')),
                                                    Forms\Components\TimePicker::make('jam_masuk_selesai')
                                                        ->label('Batas Keterlambatan')
                                                        ->required(fn (Get $get): bool => ! $get('is_libur'))
                                                        ->nullable()
                                                        ->visible(fn (Get $get): bool => ! $get('is_libur')),
                                                ]),
                                                Forms\Components\Group::make([
                                                    Forms\Components\TimePicker::make('jam_istirahat')
                                                        ->label('Mulai Istirahat')
                                                        ->required(fn (Get $get): bool => ! $get('is_libur'))
                                                        ->nullable()
                                                        ->visible(fn (Get $get): bool => ! $get('is_libur')),
                                                    Forms\Components\TimePicker::make('jam_istirahat_selesai')
                                                        ->label('Selesai Istirahat')
                                                        ->required(fn (Get $get): bool => ! $get('is_libur'))
                                                        ->nullable()
                                                        ->visible(fn (Get $get): bool => ! $get('is_libur')),
                                                ]),
                                                Forms\Components\Group::make([
                                                    Forms\Components\TimePicker::make('jam_pulang')
                                                        ->label('Jam Pulang')
                                                        ->required(fn (Get $get): bool => ! $get('is_libur'))
                                                        ->nullable()
                                                        ->visible(fn (Get $get): bool => ! $get('is_libur')),
                                                    Forms\Components\TimePicker::make('jam_pulang_selesai')
                                                        ->label('Batas Kepulangan')
                                                        ->required(fn (Get $get): bool => ! $get('is_libur'))
                                                        ->nullable()
                                                        ->visible(fn (Get $get): bool => ! $get('is_libur')),
                                                ]),
                                            ])
                                            ->visible(fn (Get $get): bool => ! $get('is_libur')),
                                    ])
                                    ->defaultItems(7) // Changed from 7 to 0
                                    ->addActionLabel('Tambah Jadwal Harian')
                                    ->columnSpanFull()
                                    ->itemLabel(fn (array $state): ?string => $state['hari'] ?? null)
                                    ->reorderableWithButtons()
                                    ->collapsible()
                            ])
                    ])->columns(2),
                Forms\Components\Toggle::make('status')
                    ->default(true)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('npsn')
                    ->label('NPSN')
                    ->searchable()
                    ->sortable()
                    ->copyable(), // NPSN biasanya perlu dicopy

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable()
                    ->wrap(), // Nama sekolah bisa panjang, perlu wrap

                Tables\Columns\TextColumn::make('jenjang')
                    ->searchable()
                    ->sortable()
                    ->badge() // Jenjang bagus ditampilkan sebagai badge
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nik_kepala')
                    ->searchable()
                    ->copyable() // NIK perlu dicopy
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('nama_kepala')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Kolom lokasi biasanya difilter, tidak perlu sort
                Tables\Columns\TextColumn::make('provinsi.nama')
                    ->url(fn ($record) => route('filament.admin.resources.provinsis.view', ['record' => $record->provinsi->id]))
                    ->icon('heroicon-m-building-library')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Klik untuk melihat detail Provinsi') // Tambahkan searchable karena ini kolom penting
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('kota.nama')
                    ->url(fn ($record) => route('filament.admin.resources.kotas.view', ['record' => $record->kota->id]))
                    ->icon('heroicon-m-building-library')
                    ->color('success')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Klik untuk melihat detail Kota') // Tambahkan searchable karena ini kolom penting
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('no_telp')
                    ->url(function ($record) {
                        $phone = $record->no_telp;
                        $phone = preg_replace('/[^0-9]/', '', $phone);

                        if (Str::startsWith($phone, '0')) {
                            $phone = Str::substr($phone, 1);
                        }

                        $phone = '62' . $phone;

                        return 'https://wa.me/' . $phone;
                    })
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-phone')
                    ->color('primary')
                    ->badge()
                    ->searchable()
                    ->tooltip('Klik untuk chat WhatsApp'), // Nomor telepon sering perlu dicopy

                Tables\Columns\TextColumn::make('email')
                    ->url(fn ($record) => 'mailto:' . $record->email)
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-envelope')
                    ->color('success')
                    ->badge()
                    ->searchable()
                    ->tooltip('Klik untuk mengirim email'), // Email sering perlu dicopy

                Tables\Columns\TextColumn::make('logo')
                    ->toggleable(isToggledHiddenByDefault: true), // Logo sebaiknya ImageColumn

                Tables\Columns\TextColumn::make('timezone')
                    ->searchable()
                    ->badge() // Display as a badge
                    ->color(function ($state) {
                        return match ($state) {
                            'WIB' => 'success',   // Green color for GMT+7
                            'WITA' => 'warning',  // Yellow color for GMT+8
                            'WIT' => 'danger',    // Red color for GMT+9
                            default => 'secondary', // Default gray color for others
                        };
                    })
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'WIB' => 'GMT+7',
                            'WITA' => 'GMT+8',
                            'WIT' => 'GMT+9',
                            default => $state,
                        };
                    })
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('siswa_count')
                    ->label('Jumlah Siswa')
                    ->counts('siswa')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Timestamp columns
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->label('Status Arsip')
                    ->native(false),

                // Filter NPSN
                Filter::make('npsn')
                    ->form([
                        Forms\Components\TextInput::make('npsn')
                            ->label('NPSN')
                            ->placeholder('Cari NPSN...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['npsn'],
                            fn (Builder $query, $value): Builder => $query->where('npsn', 'like', "%{$value}%")
                        );
                    }),

                // Filter Nama Sekolah
                Filter::make('nama')
                    ->form([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama Sekolah')
                            ->placeholder('Cari nama sekolah...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nama'],
                            fn (Builder $query, $value): Builder => $query->where('nama', 'like', "%{$value}%")
                        );
                    }),

                // Filter Nama Kepala Sekolah
                Filter::make('nama_kepala')
                    ->form([
                        Forms\Components\TextInput::make('nama_kepala')
                            ->label('Nama Kepala Sekolah')
                            ->placeholder('Cari nama kepala sekolah...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nama_kepala'],
                            fn (Builder $query, $value): Builder => $query->where('nama_kepala', 'like', "%{$value}%")
                        );
                    }),

                // Filter lainnya tetap sama seperti sebelumnya
                SelectFilter::make('jenjang')
                    ->label('Jenjang')
                    ->multiple()
                    ->options([
                        'SD' => 'SD',
                        'SMP' => 'SMP',
                        'SMA' => 'SMA',
                        'SMK' => 'SMK'
                    ])
                    ->native(false),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ])
                    ->native(false),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListSekolahs::route('/'),
            'create' => Pages\CreateSekolah::route('/create'),
            'view' => Pages\ViewSekolah::route('/{record}'),
            'edit' => Pages\EditSekolah::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);

        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            if ($user && $user->hasRole('admin_sekolah') || $user->hasRole('staff_sekolah')) {
                // If user is 'admin' or 'sekolah', filter by their sekolah_id
                $query->where('id', $user->sekolah_id);
            }
        }

        return $query;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        assert($user instanceof User);
        return $user->hasRole('super_admin') || $user->hasRole('staff') ? true : false;
    }
}
