<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensiResource\Pages;
use App\Filament\Resources\AbsensiResource\RelationManagers;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Sekolah;
use App\Models\Siswa;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Sekolah';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Absensi')
                    ->description('Masukan data absensi siswa')
                    ->schema([
                        Forms\Components\Select::make('sekolah_id')
                            ->required()
                            ->relationship(name: 'sekolah', titleAttribute: 'nama')
                            ->searchable()
                            ->default(fn() => Auth::user()->sekolah_id ?? null)
                            ->hidden(fn() => Auth::user()->sekolah_id !== null)
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                // Reset semua nilai terkait
                                $set('kelas_filter', null);
                                $set('uid', null);
                                $set('tanggal', null);
                                $set('waktu', null);
                                $set('keterangan', null);
                                $set('is_loading_datetime', false);

                                // Jika tidak ada sekolah yang dipilih, langsung return
                                if (!$sekolahId = $get('sekolah_id')) {
                                    return;
                                }

                                // Cek dan set default tanggal & waktu berdasarkan timezone sekolah
                                $timezone = cache()->remember(
                                    "sekolah_timezone_{$sekolahId}",
                                    now()->addMinutes(30),
                                    fn() => Sekolah::find($sekolahId)?->timezone
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

                        Forms\Components\Select::make('kelas_filter')
                            ->label('Kelas')
                            ->options(
                                fn(Get $get): Collection =>
                                $get('sekolah_id')
                                    ? Kelas::where('sekolah_id', $get('sekolah_id'))->pluck('nama_kelas', 'id')
                                    : collect()
                            )
                            ->searchable()
                            ->live()
                            ->afterStateHydrated(function ($state, callable $set) {
                                if (Route::currentRouteName() === 'filament.admin.resources.absensis.edit' || Route::currentRouteName() === 'filament.admin.resources.absensis.view') {
                                    $id = Request::route('record');
                                    $absensi = Absensi::find($id);
                                    $set('kelas_filter', $absensi->siswa->kelas_id ?? null);
                                }
                            })
                            ->afterStateUpdated(function (callable $set) {
                                $set('uid', null);
                            }),


                        Forms\Components\Select::make('siswa_id')
                            ->required()
                            ->label('Siswa')
                            ->options(
                                fn(Get $get): Collection =>
                                $get('sekolah_id')
                                    ? cache()->remember(
                                        "siswa_options_{$get('sekolah_id')}_" .
                                            ($get('kelas_filter') ? "kelas_{$get('kelas_filter')}" : 'all'),
                                        now()->addMinutes(5),
                                        fn() => Siswa::query()
                                            ->where('sekolah_id', $get('sekolah_id'))
                                            ->when($get('kelas_filter'), fn($query) => $query->where('kelas_id', $get('kelas_filter')))
                                            ->get()
                                            ->mapWithKeys(fn($siswa) => [
                                                $siswa->id => "{$siswa->nama} - {$siswa->kelas->nama_kelas}" // ID sebagai key
                                            ])
                                    )
                                    : collect()
                            )
                            ->searchable()
                            ->preload()
                            ->disabled(fn(Get $get): bool => !$get('sekolah_id')),

                        Forms\Components\DatePicker::make('tanggal')
                            ->required()
                            ->default(function () {
                                if (Auth::user()->sekolah_id) {
                                    $timezone = cache()->remember(
                                        "sekolah_timezone_" . Auth::user()->sekolah_id,
                                        now()->addMinutes(30),
                                        fn() => Auth::user()->sekolah->timezone
                                    );

                                    if ($timezone) {
                                        $timezone = match ($timezone) {
                                            'WIB' => 'Asia/Jakarta',
                                            'WITA' => 'Asia/Makassar',
                                            'WIT' => 'Asia/Jayapura',
                                            default => 'Asia/Jakarta'
                                        };

                                        return now()->timezone($timezone)->format('Y-m-d');
                                    }

                                    return now()->format('Y-m-d');
                                }

                                return null;
                            })
                            ->live()
                            ->afterStateUpdated(function (Get $get, callable $set, $state) {
                                try {
                                    $sekolahId = $get('sekolah_id');
                                    $waktu = $get('waktu');

                                    if ($sekolahId && $state && $waktu) {
                                        $sekolah = Sekolah::findOrFail($sekolahId);
                                        $schoolTimezone = match ($sekolah->timezone) {
                                            'WIB' => 'Asia/Jakarta',
                                            'WITA' => 'Asia/Makassar',
                                            'WIT' => 'Asia/Jayapura',
                                            default => 'Asia/Jakarta'
                                        };

                                        $inputDateTime = Carbon::parse($state . ' ' . $waktu, $schoolTimezone);
                                        $dayOfWeek = $inputDateTime->locale('id')->dayName;

                                        $jadwal = $sekolah->jadwalHarian()->where('hari', $dayOfWeek)->first();

                                        if ($jadwal) {
                                            $jamMasuk = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk, $schoolTimezone);
                                            $jamMasukSelesai = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk_selesai, $schoolTimezone);
                                            $jamPulang = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_pulang, $schoolTimezone);
                                            $jamPulangSelesai = $jamPulang->copy()->addHour();

                                            if ($inputDateTime->between($jamMasuk, $jamMasukSelesai)) {
                                                $set('keterangan', 'Masuk');
                                            } elseif ($inputDateTime->gt($jamMasukSelesai) && $inputDateTime->lt($jamPulang)) {
                                                $set('keterangan', 'Terlambat');
                                            } elseif ($inputDateTime->between($jamPulang, $jamPulangSelesai)) {
                                                $set('keterangan', 'Pulang');
                                            } else {
                                                $set('keterangan', null);
                                            }
                                        }
                                    }
                                } catch (\Exception $e) {
                                    $set('keterangan', null);
                                }
                            })
                            ->disabled(fn(Get $get): bool => (bool) $get('is_loading_datetime')),

                        Forms\Components\TimePicker::make('waktu')
                            ->required()
                            ->default(function () {
                                if (Auth::user()->sekolah_id) {
                                    $timezone = cache()->remember(
                                        "sekolah_timezone_" . Auth::user()->sekolah_id,
                                        now()->addMinutes(30),
                                        fn() => Auth::user()->sekolah->timezone
                                    );

                                    if ($timezone) {
                                        $timezone = match ($timezone) {
                                            'WIB' => 'Asia/Jakarta',
                                            'WITA' => 'Asia/Makassar',
                                            'WIT' => 'Asia/Jayapura',
                                            default => 'Asia/Jakarta'
                                        };

                                        return now()->timezone($timezone)->format('H:i');
                                    }

                                    return now()->format('H:i');
                                }

                                return null;
                            })
                            ->live()
                            ->afterStateUpdated(function (Get $get, callable $set, $state) {
                                try {
                                    $sekolahId = $get('sekolah_id');
                                    $tanggal = $get('tanggal');

                                    if ($sekolahId && $tanggal && $state) {
                                        $sekolah = Sekolah::findOrFail($sekolahId);
                                        $schoolTimezone = match ($sekolah->timezone) {
                                            'WIB' => 'Asia/Jakarta',
                                            'WITA' => 'Asia/Makassar',
                                            'WIT' => 'Asia/Jay pura',
                                            default => 'Asia/Jakarta'
                                        };

                                        $inputDateTime = Carbon::parse($tanggal . ' ' . $state, $schoolTimezone);
                                        $dayOfWeek = $inputDateTime->locale('id')->dayName;

                                        $jadwal = $sekolah->jadwalHarian()->where('hari', $dayOfWeek)->first();

                                        if ($jadwal) {
                                            $jamMasuk = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk, $schoolTimezone);
                                            $jamMasukSelesai = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk_selesai, $schoolTimezone);
                                            $jamPulang = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_pulang, $schoolTimezone);
                                            $jamPulangSelesai = $jamPulang->copy()->addHour();

                                            if ($inputDateTime->between($jamMasuk, $jamMasukSelesai)) {
                                                $set('keterangan', 'Masuk');
                                            } elseif ($inputDateTime->gt($jamMasukSelesai) && $inputDateTime->lt($jamPulang)) {
                                                $set('keterangan', 'Terlambat');
                                            } elseif ($inputDateTime->between($jamPulang, $jamPulangSelesai)) {
                                                $set('keterangan', 'Pulang');
                                            } else {
                                                $set('keterangan', null);
                                            }
                                        }
                                    }
                                } catch (\Exception $e) {
                                    $set('keterangan', null);
                                }
                            })
                            ->disabled(fn(Get $get): bool => (bool) $get('is_loading_datetime')),

                        Forms\Components\Select::make('keterangan')
                            ->options([
                                'Masuk' => 'Masuk',
                                'Terlambat' => 'Terlambat',
                                'Pulang' => 'Pulang',
                                'Sakit' => 'Sakit',
                                'Izin' => 'Izin',
                                'Alpa' => 'Alpa'
                            ])
                            ->native(false)
                            ->default(function (Get $get) {
                                $sekolahId = $get('sekolah_id');
                                $tanggal = $get('tanggal');
                                $waktu = $get('waktu');

                                if ($sekolahId && $tanggal && $waktu) {
                                    try {
                                        $sekolah = Sekolah::findOrFail($sekolahId);
                                        $schoolTimezone = match ($sekolah->timezone) {
                                            'WIB' => 'Asia/Jakarta',
                                            'WITA' => 'Asia/Makassar',
                                            'WIT' => 'Asia/Jayapura',
                                            default => 'Asia/Jakarta'
                                        };

                                        $inputDateTime = Carbon::parse($tanggal . ' ' . $waktu, $schoolTimezone);
                                        $dayOfWeek = $inputDateTime->locale('id')->dayName;

                                        $jadwal = $sekolah->jadwalHarian()->where('hari', $dayOfWeek)->first();

                                        if ($jadwal) {
                                            $jamMasuk = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk, $schoolTimezone);
                                            $jamMasukSelesai = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_masuk_selesai, $schoolTimezone);
                                            $jamPulang = Carbon::parse($inputDateTime->format('Y-m-d') . ' ' . $jadwal->jam_pulang, $schoolTimezone);
                                            $jamPulangSelesai = $jamPulang->copy()->addHour();

                                            if ($inputDateTime->between($jamMasuk, $jamMasukSelesai)) {
                                                return 'Masuk';
                                            } elseif ($inputDateTime->gt($jamMasukSelesai) && $inputDateTime->lt($jamPulang)) {
                                                return 'Terlambat';
                                            } elseif ($inputDateTime->between($jamPulang, $jamPulangSelesai)) {
                                                return 'Pulang';
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        // Log error if needed
                                    }
                                }

                                return null;
                            })
                            ->nullable(),
                    ])->columns(2)
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
                // Timestamp Columns
                Tables\Columns\TextColumn::make('tanggal')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn($record) => Carbon::parse($record->tanggal)->translatedFormat('d M Y')),

                Tables\Columns\TextColumn::make('waktu')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn($record) => Carbon::parse($record->waktu)->format('H:i')),

                // School and Student Info
                Tables\Columns\TextColumn::make('sekolah.nama')
                    ->url(fn($record) => route('filament.admin.resources.sekolahs.view', ['record' => $record->sekolah_id]))
                    ->hidden(fn() => Auth::user()->sekolah_id != null)
                    ->icon('heroicon-m-building-library')
                    ->color('success')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Klik untuk melihat detail sekolah'),

                Tables\Columns\TextColumn::make('siswa.nama')
                    ->url(fn($record) => route('filament.admin.resources.siswas.view', ['record' => $record->siswa->id]))
                    ->icon('heroicon-m-academic-cap')
                    ->color('primary')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->tooltip('Klik untuk melihat detail siswa'),

                Tables\Columns\TextColumn::make('siswa.kelas.nama_kelas')
                    ->url(fn($record) => route('filament.admin.resources.kelas.view', ['record' => $record->siswa->kelas->id]))
                    ->icon('heroicon-m-user-group')
                    ->color('primary')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->tooltip('Klik untuk melihat detail kelas siswa'),

                // Attendance Status
                Tables\Columns\TextColumn::make('keterangan')
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
                    ->badge()
                    ->searchable()
                    ->sortable(),

                // Additional Info
                Tables\Columns\TextColumn::make('uid')
                    ->label('Kode Absensi')
                    ->sortable()
                    ->searchable()
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
            ->defaultSort('tanggal')
            ->filters([
                // Date & Time Filters
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from_date')
                            ->label('Dari Tanggal')
                            ->default(now()),
                        Forms\Components\DatePicker::make('until_date')
                            ->label('Sampai Tanggal')
                            ->default(now()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date)
                            )
                            ->when(
                                $data['until_date'],
                                fn(Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date)
                            );
                    })
                    ->columns(2)->columnSpan(Auth::user()->sekolah_id ? 3 : 2),

                // School & Class Filters
                SelectFilter::make('sekolah')
                    ->relationship('sekolah', 'nama')
                    ->label('Sekolah')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->hidden(fn() => Auth::user()->sekolah_id != null),

                Filter::make('waktu')
                    ->form([
                        Forms\Components\TextInput::make('waktu_from')
                            ->label('Dari Jam')
                            ->type('time'),
                        Forms\Components\TextInput::make('waktu_until')
                            ->label('Sampai Jam')
                            ->type('time'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['waktu_from'],
                                fn(Builder $query, $time): Builder => $query->whereTime('waktu', '>=', $time)
                            )
                            ->when(
                                $data['waktu_until'],
                                fn(Builder $query, $time): Builder => $query->whereTime('waktu', '<=', $time)
                            );
                    })
                    ->columns(2)->columnSpan(2),

                SelectFilter::make('kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->options(function () {
                        // Mengambil semua kelas jika user tidak memiliki sekolah_id atau vendor_id
                        if (!Auth::user()->sekolah_id && !Auth::user()->vendor_id) {
                            return Kelas::query()
                                ->with('sekolah') // Mengambil relasi sekolah
                                ->get()
                                ->mapWithKeys(function ($kelas) {
                                    return [
                                        $kelas->id => "{$kelas->nama_kelas} - {$kelas->sekolah->nama}" // Menampilkan nama kelas dan nama sekolah
                                    ];
                                });
                        }

                        // Mengambil kelas berdasarkan sekolah_id jika ada
                        return Kelas::query()
                            ->where('sekolah_id', Auth::user()->sekolah_id)
                            ->pluck('nama_kelas', 'id');
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn(Builder $query, $kelasId): Builder => $query->whereHas('siswa', function (Builder $q) use ($kelasId) {
                                $q->where('kelas_id', $kelasId);
                            })
                        );
                    }),

                // Student Filter
                Filter::make('siswa')
                    ->form([
                        Forms\Components\TextInput::make('siswa_nama')
                            ->label('Nama Siswa')
                            ->placeholder('Cari nama siswa...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['siswa_nama'],
                            fn(Builder $query, $value): Builder => $query->whereHas(
                                'siswa',
                                fn($q) =>
                                $q->where('nama', 'like', "%{$value}%")
                            )
                        );
                    }),

                // Attendance Status Filter
                SelectFilter::make('keterangan')
                    ->label('Status Kehadiran')
                    ->options([
                        'Masuk' => 'Masuk',
                        'Pulang' => 'Pulang',
                        'Terlambat' => 'Terlambat',
                        'Izin' => 'Izin',
                        'Sakit' => 'Sakit',
                        'Alpa' => 'Alpa',
                    ])
                    ->native(false),

                // UID Filter
                Filter::make('uid')
                    ->form([
                        Forms\Components\TextInput::make('uid')
                            ->label('Kode Absensi')
                            ->placeholder('Cari Kode Abensi...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['uid'],
                            fn(Builder $query, $value): Builder => $query->where('uid', 'like', "%{$value}%")
                        );
                    }),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                // Attendance Basic Information Section
                Section::make('Informasi Absensi')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // School Information
                                TextEntry::make('sekolah.nama')
                                    ->label('Sekolah')
                                    ->icon('heroicon-m-building-library')
                                    ->color('primary')
                                    ->badge()
                                    ->url(fn($record) => route('filament.admin.resources.sekolahs.view', ['record' => $record->sekolah_id]))
                                    ->hidden(fn() => Auth::user()->sekolah_id !== null),

                                // Student Information
                                TextEntry::make('siswa.nama')
                                    ->label('Nama Siswa')
                                    ->icon('heroicon-m-user')
                                    ->color('info')
                                    ->badge()
                                    ->url(fn($record) => route('filament.admin.resources.siswas.view', ['record' => $record->siswa_id]))
                            ]),

                        Grid::make(2)
                            ->schema([
                                // Date and Time of Attendance
                                TextEntry::make('tanggal')
                                    ->label('Tanggal')
                                    ->date('d M Y')
                                    ->icon('heroicon-m-calendar-days'),

                                TextEntry::make('waktu')
                                    ->label('Waktu')
                                    ->icon('heroicon-m-clock')
                            ]),

                        // Attendance Status
                        TextEntry::make('keterangan')
                            ->label('Status Kehadiran')
                            ->badge()
                            ->color(fn($state) => match ($state) {
                                'Masuk' => 'success',
                                'Terlambat' => 'warning',
                                'Pulang' => 'info',
                                'Sakit' => 'primary',
                                'Izin' => 'secondary',
                                'Alpa' => 'danger',
                                default => 'gray'
                            })
                    ]),

                // Student Details Section
                Section::make('Detail Siswa')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                // Student Class
                                TextEntry::make('siswa.kelas.nama_kelas')
                                    ->label('Kelas')
                                    ->icon('heroicon-m-user-group')
                                    ->color('primary')
                                    ->badge(),

                                // Student Gender
                                TextEntry::make('siswa.jk')
                                    ->label('Jenis Kelamin')
                                    ->formatStateUsing(fn($state) => $state === 'L' ? 'Laki-laki' : 'Perempuan')
                                    ->color(fn($record) => $record->siswa->jk === 'L' ? 'info' : 'danger')
                                    ->badge(),

                                // Student Parent Contact
                                TextEntry::make('siswa.telp_ortu')
                                    ->label('Telepon Orang Tua')
                                    ->icon('heroicon-m-phone')
                                    ->color('primary')
                                    ->url(function ($record) {
                                        $phone = $record->siswa->telp_ortu;
                                        $phone = preg_replace('/[^0-9]/', '', $phone);
                                        $phone = Str::startsWith($phone, '0') ? Str::substr($phone, 1) : $phone;
                                        $phone = '62' . $phone;
                                        return 'https://wa.me/' . $phone;
                                    })
                                    ->openUrlInNewTab()
                            ])
                    ]),

                // School Schedule Context Section
                Section::make('Konteks Jadwal Sekolah')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                // Day of Week
                                TextEntry::make('day_of_week')
                                    ->label('Hari')
                                    ->state(fn($record) => Carbon::parse($record->tanggal)->locale('id')->dayName)
                                    ->icon('heroicon-m-calendar'),

                                // School Timezone
                                TextEntry::make('sekolah.timezone')
                                    ->label('Zona Waktu Sekolah')
                                    ->icon('heroicon-m-globe-alt')
                            ]),

                        // School Daily Schedule for the Day
                        RepeatableEntry::make('sekolah.jadwalHarian')
                            ->label('Jadwal Harian Sekolah')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('hari')
                                            ->label('Hari'),

                                        TextEntry::make('is_libur')
                                            ->label('Status Hari')
                                            ->badge()
                                            ->color(fn($state) => $state ? 'danger' : 'success')
                                            ->formatStateUsing(fn($state) => $state ? 'Libur' : 'Aktif'),

                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('jam_masuk')
                                                    ->label('Jam Masuk')
                                                    ->icon('heroicon-m-clock'),

                                                TextEntry::make('jam_pulang')
                                                    ->label('Jam Pulang')
                                                    ->icon('heroicon-m-clock')
                                            ])
                                    ])
                            ])
                            ->grid(2)
                            ->visible(fn($record) => $record->sekolah->jadwalHarian->isNotEmpty())
                    ]),

                // Metadata Section
                Section::make('Informasi Tambahan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Dibuat Pada')
                                    ->dateTime(),

                                TextEntry::make('updated_at')
                                    ->label('Terakhir Diubah')
                                    ->dateTime(),

                                TextEntry::make('deleted_at')
                                    ->label('Dihapus Pada')
                                    ->dateTime(),
                            ])
                    ])
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
            if ($user && $user->hasRole('admin_sekolah') || $user->hasRole('sekoalah')) {
                // If user is 'admin' or 'sekolah', filter by their sekolah_id
                $query->where('sekolah_id', $user->sekolah_id);
            }
        }

        return $query;
    }
}
