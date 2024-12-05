<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Filament\Resources\SiswaResource\RelationManagers;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Sekolah';

    protected static ?int $navigationSort = 3;

    protected static int $globalSearchResultsLimit = 20;

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        return $form
            ->schema([
                Forms\Components\Section::make('Data Siswa')
                ->description('Masukan data siswa')
                ->schema([
                    Forms\Components\Select::make('sekolah_id')
                        ->label('Sekolah')
                        ->required()
                        ->relationship(name: 'sekolah', titleAttribute: 'nama')
                        ->searchable()
                        ->default(fn () => Auth::user()->sekolah_id ?? null)
                        ->hidden(fn () => Auth::user()->sekolah_id !== null)
                        ->preload()
                        ->afterStateUpdated(function (callable $set) {
                            $set('kelas', null);
                        }),
                    Forms\Components\TextInput::make('nis')
                        ->label('NIS')
                        ->required()
                        ->unique(Siswa::class, 'nis', ignoreRecord: true)
                        ->numeric(),
                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('panggilan')
                        ->label('Nama Panggilan')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Repeater::make('uidType')
                        ->relationship()
                        ->label('UID')
                        ->schema([
                            Forms\Components\Select::make('type')
                                ->label('Tipe')
                                ->options([
                                    'rfid' => 'RFID',
                                    'fingerprint' => 'Fingerprint',
                                    'retina' => 'Retina',
                                    'face_id' => 'Face ID',
                                ])
                                ->native(false)
                                ->required(),
                                Forms\Components\TextInput::make('value')
                                    ->label('Kode Absensi')
                                    ->password() // Mengatur input sebagai password
                                    ->revealable(),
                        ])
                        ->default([
                            ['type' => 'rfid', 'value' => null],
                            ['type' => 'fingerprint', 'value' => null],
                            ['type' => 'retina', 'value' => null],
                            ['type' => 'face_id', 'value' => null],
                        ])
                        // ->addable($user->hasRole('super_admin'))
                        ->columnSpanFull(),
                ])
                ->columns(2), // Optional: Set columns to 2 for better layout in this section

                Forms\Components\Section::make('Data Kelas & Lainnya')
                ->description('Masukan data kelas siswa')
                ->schema([
                    Forms\Components\Select::make('kelas_id')
                        ->label('Kelas')
                        ->required()
                        ->options(fn (Forms\Get $get): Collection => Kelas::query()->where('sekolah_id', $get('sekolah_id'))->get()->pluck('nama_kelas', 'id'))
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('jk')
                        ->label('Jenis Kelamin')
                        ->required()
                        ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                        ->native(false),
                    Forms\Components\TextInput::make('telp_ortu')
                        ->label('Telepon Orang Tua')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                        ])
                        ->columns(2),
                Forms\Components\Toggle::make('status')
                    ->label('Status')
                    ->required(),
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
                Tables\Columns\TextColumn::make('sekolah.nama')
                    ->url(fn ($record) => route('filament.admin.resources.sekolahs.view', ['record' => $record->sekolah_id]))
                    ->hidden(fn () => Auth::user()->sekolah_id != null)
                    ->icon('heroicon-m-building-library')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Klik untuk melihat detail sekolah'), // Tambahkan searchable karena ini kolom penting

                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable() // Tambahkan searchable karena nomor identitas
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('panggilan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->url(function ($record) {
                        $kelas = Kelas::query()
                            ->where('nama_kelas', $record->kelas)
                            ->where('sekolah_id', $record->sekolah_id)
                            ->first();

                        return $kelas
                            ? route('filament.admin.resources.kelas.view', ['record' => $kelas->id])
                            : null;
                    })
                    ->icon('heroicon-m-user-group')
                    ->color('primary')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->tooltip('Klik untuk melihat detail kelas'),

                Tables\Columns\TextColumn::make('jk')
                    ->label('Jenis Kelamin')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                    ->color(fn ($record) => $record->jk === 'L' ? 'info' : 'danger')
                    ->badge(),

                Tables\Columns\TextColumn::make('telp_ortu')
                    ->url(function ($record) {
                        $phone = $record->telp_ortu;

                        // Hapus semua karakter non-digit
                        $phone = preg_replace('/[^0-9]/', '', $phone);

                        // Jika dimulai dengan '0', hapus '0' tersebut
                        if (Str::startsWith($phone, '0')) {
                            $phone = Str::substr($phone, 1);
                        }

                        // Tambahkan '62' di depan nomor
                        $phone = '62' . $phone;

                        return 'https://wa.me/' . $phone;
                    })
                    ->openUrlInNewTab()
                    ->icon('heroicon-m-phone')
                    ->color('primary')
                    ->badge()
                    ->searchable()
                    ->tooltip('Klik untuk chat WhatsApp'),

                Tables\Columns\IconColumn::make('status')
                    ->boolean(),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort(fn () => Auth::user()->sekolah_id ? 'nama' : 'sekolah.nama')
            ->filters([
                // Basic Status Filters
                Tables\Filters\TrashedFilter::make()
                    ->label('Status Arsip')
                    ->native(false),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ])
                    ->native(false),

                Filter::make('nis')
                    ->form([
                        Forms\Components\TextInput::make('nis')
                            ->label('NIS')
                            ->placeholder('Cari NIS...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nis'],
                            fn (Builder $query, $value): Builder => $query->where('nis', 'like', "%{$value}%")
                        );
                    }),

                Filter::make('nama')
                    ->form([
                        Forms\Components\TextInput::make('nama')
                            ->label('Nama')
                            ->placeholder('Cari nama...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['nama'],
                            fn (Builder $query, $value): Builder => $query->where('nama', 'like', "%{$value}%")
                        );
                    }),

                // Kelas & Sekolah Filters
                SelectFilter::make('kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->options(function ($request) {
                        $user = $request->user();
                        return Kelas::where('sekolah_id', $user->sekolah_id)
                            ->pluck('nama_kelas', 'id');
                    }),

                SelectFilter::make('sekolah')
                    ->relationship('sekolah', 'nama')
                    ->label('Sekolah')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->hidden(fn () => Auth::user()->sekolah_id != null),

                // Demographic Filters
                SelectFilter::make('jk')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->native(false),

                // Date Range Filter
                Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal'),
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
                            $indicators['created_from'] = 'Created from ' . Carbon::parse($data['created_from'])->format('F d, Y');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Created until ' . Carbon::parse($data['created_until'])->format('F d, Y');
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
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'view' => Pages\ViewSiswa::route('/{record}'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
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
                $query->where('sekolah_id', $user->sekolah_id);
            }
        }

        return $query;
    }

    // Global Search
    public static function getGloballySearchableAttributes(): array
    {
        $user = Auth::user();
        assert($user instanceof User);
        return $user->hasRole('super_admin') || $user->hasRole('staff') ? ['nama', 'sekolah.nama', 'kelas.nama_kelas', 'nis', 'uid'] : ['nama', 'kelas.nama_kelas', 'nis', 'uid'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->nama;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $result = [];
        if (Auth::user()->sekolah_id != null) {
            $result = [
                "Kelas: " => $record->kelas->nama_kelas,
                "Jenis Kelamin: " => $record->jk == 'L' ? 'Laki-laki' : 'Perempuan',
            ];
        } else {
            $result = [
                "Sekolah: " => $record->sekolah->nama
            ];
        }
        return $result;
    }
}
