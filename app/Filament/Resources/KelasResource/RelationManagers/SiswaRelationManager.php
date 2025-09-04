<?php

namespace App\Filament\Resources\KelasResource\RelationManagers;

use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SiswaRelationManager extends RelationManager
{
    protected static string $relationship = 'siswa';

    public function form(Form $form): Form
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama')
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
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
