<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Kota;
use App\Models\User;
use App\Models\Vendor;
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
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationGroup = 'Vendor';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Vendor Information')
                ->description('Enter Vendor Information')
                ->schema([
                    Forms\Components\TextInput::make('nama')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique(Vendor::class, 'email', ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('no_telp')
                        ->tel()
                        ->required()
                        ->unique(Vendor::class, 'no_telp', ignoreRecord: true)
                        ->maxLength(255)
                ]),
                Forms\Components\Section::make('Address Information')
                ->description('Enter Vendor Information')
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
                        ->options(fn (Get $get): Collection => Kota::query()->where('provinsi_code', $get('provinsi_code'))->get()->pluck('nama', 'code'))
                        ->searchable()
                        ->preload()
                        ->columnSpanFull(),
                ]),
                Forms\Components\Toggle::make('status')
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
            Tables\Columns\TextColumn::make('nama')
                ->label('Nama')
                ->searchable()
                ->sortable() // Nama biasanya perlu diurutkan
                ->tooltip('Nama lengkap')
                ->wrap(), // Untuk nama yang panjang

            Tables\Columns\TextColumn::make('no_telp')
                ->url(function ($record) {
                    $phone = $record->no_telp;

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

            Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->url(fn ($record) => 'mailto:' . $record->email)
                ->openUrlInNewTab()
                ->icon('heroicon-m-envelope')
                ->color('success')
                ->badge()
                ->searchable() // Email perlu dicari
                ->sortable() // Email bisa diurutkan
                ->tooltip('Klik untuk kirim email'),

            Tables\Columns\TextColumn::make('provinsi.nama')
                ->url(fn ($record) => route('filament.admin.resources.provinsis.view', ['record' => $record->provinsi->id]))
                ->icon('heroicon-m-building-library')
                ->color('primary')
                ->badge()
                ->sortable()
                ->searchable()
                ->tooltip('Klik untuk melihat detail Provinsi'), // Tambahkan searchable karena ini kolom penting

            Tables\Columns\TextColumn::make('kota.nama')
                ->url(fn ($record) => route('filament.admin.resources.kotas.view', ['record' => $record->kota->id]))
                ->icon('heroicon-m-building-library')
                ->color('success')
                ->badge()
                ->sortable()
                ->searchable()
                ->tooltip('Klik untuk melihat detail Kota'), // Tambahkan searchable karena ini kolom penting

            Tables\Columns\TextColumn::make('mesin_count')
                ->label('Jumlah Mesin')
                ->counts('mesin')
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\IconColumn::make('status')
                ->label('Status')
                ->boolean()
                ->sortable() // Status biasanya perlu diurutkan
                ->tooltip('Status aktif/nonaktif')
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->trueColor('success')
                ->falseColor('danger'),

            Tables\Columns\TextColumn::make('deleted_at')
                ->label('Tanggal Dihapus')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Dibuat')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            Tables\Columns\TextColumn::make('updated_at')
                ->label('Tanggal Diperbarui')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->defaultSort('nama')
        ->filters([
            // Basic Status Filters
            Tables\Filters\TrashedFilter::make()
                ->label('Status Arsip')
                ->native(false),

            SelectFilter::make('status')
                ->label('Status')
                ->options([
                    true => 'Aktif',
                    false => 'Nonaktif',
                ])
                ->native(false),

            // Main Data Filters
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

             // Location Filters
             Filter::make('location')
             ->form([
                 Forms\Components\TextInput::make('provinsi')
                     ->label('Provinsi')
                     ->placeholder('Cari provinsi...'),
                 Forms\Components\TextInput::make('kota')
                     ->label('Kota')
                     ->placeholder('Cari kota...'),
             ])
             ->query(function (Builder $query, array $data): Builder {
                 return $query
                     ->when(
                         $data['provinsi'],
                         fn (Builder $query, $value): Builder => $query->where('provinsi', 'like', "%{$value}%")
                     )
                     ->when(
                         $data['kota'],
                         fn (Builder $query, $value): Builder => $query->where('kota', 'like', "%{$value}%")
                     );
             })
             ->columns(2)->columnSpan(3),

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
                        $indicators['created_from'] = 'Dibuat dari ' . Carbon::parse($data['created_from'])->format('d/m/Y');
                    }
                    if ($data['created_until'] ?? null) {
                        $indicators['created_until'] = 'Dibuat sampai ' . Carbon::parse($data['created_until'])->format('d/m/Y');
                    }
                    return $indicators;
                })
                ->columns(2)->columnSpan(3)
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
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'view' => Pages\ViewVendor::route('/{record}'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
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
            if ($user && $user->hasRole('vendor')) {
                // If user is vendor, filter by their vendor_id
                $query->where('id', $user->vendor_id);
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
