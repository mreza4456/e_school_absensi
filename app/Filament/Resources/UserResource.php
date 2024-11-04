<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\Widgets\UserOverview;
use App\Filament\Resources\UserResource\Widgets\YourModelWidget;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Manajemen Pengguna';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $user = Auth::user();
        assert($user instanceof User);

        return $form
            ->schema([
                Forms\Components\FileUpload::make('avatar_url')
                    ->image()
                    ->avatar()
                    ->directory('users-profiles')
                    ->visibility('public')
                    ->columnSpanFull()
                    ->imageEditor()
                    ->circleCropper()
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('400')
                    ->imageResizeTargetHeight('400'),
                Forms\Components\Select::make('sekolah_id')
                    ->hidden(fn () => !$user->hasRole('super_admin') || Auth::user()->sekolah_id !== null || Auth::user()->vendor_id !== null)
                    ->disabled(fn (Get $get) => filled($get('vendor_id')))
                    ->nullable(fn () => $user->hasRole('super_admin'))
                    ->required(fn () => !$user->hasRole('super_admin'))
                    ->relationship(name: 'sekolah', titleAttribute: 'nama')
                    ->searchable()
                    ->live()
                    ->preload(),

                Forms\Components\Select::make('vendor_id')
                    ->hidden(fn () => !$user->hasRole('super_admin') || Auth::user()->vendor_id !== null || Auth::user()->sekolah_id !== null)
                    ->disabled(fn (Get $get) => filled($get('sekolah_id')))
                    ->nullable(fn () => $user->hasRole('super_admin'))
                    ->required(fn () => !$user->hasRole('super_admin'))
                    ->relationship(name: 'vendor', titleAttribute: 'nama')
                    ->searchable()
                    ->live()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->where('id', '!=', 2)
                    )
                    ->multiple()
                    ->preload()
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('tipe_institusi')
                    ->hidden(function ($record) {
                        return $record && $record->sekolah || $record && $record->vendor ? false : true;
                    })
                    ->label('Sekolah/Vendor')
                    ->state(function ($record) {
                        return $record->sekolah?->nama ?? $record->vendor?->nama;
                    })
                    ->badge()
                    ->color('primary')
                    ->searchable(query: function ($query, $search) {
                        return $query->whereHas('sekolah', fn($q) => $q->where('nama', 'like', "%{$search}%"))
                                    ->orWhereHas('vendor', fn($q) => $q->where('nama', 'like', "%{$search}%"));
                    })
                    ->description(function ($record) {
                        return $record->sekolah ? 'Sekolah' : 'Vendor';
                    })
                    ->url(function ($record) {
                        if ($record->sekolah) {
                            return route('filament.admin.resources.sekolahs.view', ['record' => $record->sekolah->id]);
                        } elseif ($record->vendor) {
                            return route('filament.admin.resources.vendors.view', ['record' => $record->vendor->id]);
                        }
                        return null;
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
            // Main Profile Section - Always Visible
            Section::make()
                ->schema([
                    Grid::make(2)
                        ->schema([
                            Group::make([
                                ImageEntry::make('image')
                                    ->label('Foto Profil')
                                    ->circular()
                                    ->height(150)
                                    ->width(150)
                                    ->defaultImageUrl(asset('storage/users-profiles/default.png'))
                                    ->extraAttributes([
                                        'class' => 'ring-4 ring-primary-500 ring-offset-4 shadow-lg transition-all duration-300 hover:scale-105',
                                    ]),
                            ])
                            ->columnSpan(1),

                            Group::make([
                                TextEntry::make('name')
                                    ->label('Nama Lengkap')
                                    ->weight(FontWeight::Bold)
                                    ->size(TextEntry\TextEntrySize::Large)
                                    ->icon('heroicon-o-user')
                                    ->iconPosition(IconPosition::Before)
                                    ->extraAttributes(['class' => 'text-2xl mb-2']),

                                TextEntry::make('email')
                                    ->icon('heroicon-o-envelope')
                                    ->copyable()
                                    ->copyMessage('Email berhasil disalin!')
                                    ->copyMessageDuration(1500)
                                    ->extraAttributes(['class' => 'text-gray-600']),

                                TextEntry::make('email_verified_at')
                                    ->label('Email Terverifikasi')
                                    ->dateTime('d M Y H:i')
                                    ->hidden(fn ($record) => ! $record->email_verified_at)
                                    ->icon('heroicon-o-shield-check')
                                    ->color('success')
                                    ->badge(),
                            ])
                            ->columnSpan(1)
                            ->extraAttributes(['class' => 'space-y-3']),
                        ]),
                ])
                ->collapsible(false)
                ->extraAttributes(['class' => 'bg-white shadow-sm rounded-xl mb-6']),

            // Tabbed Sections
            Tabs::make('User Information')
                ->tabs([
                    Tabs\Tab::make('Informasi Profil')
                        ->icon('heroicon-o-user-circle')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('phone')
                                        ->label('Nomor Telepon')
                                        ->icon('heroicon-o-phone')
                                        ->copyable(),

                                    TextEntry::make('address')
                                        ->label('Alamat')
                                        ->icon('heroicon-o-map-pin'),

                                    TextEntry::make('birth_date')
                                        ->label('Tanggal Lahir')
                                        ->date('d M Y')
                                        ->icon('heroicon-o-cake'),
                                ]),
                        ]),

                    Tabs\Tab::make('Informasi Institusi')
                        ->icon('heroicon-o-building-office')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('sekolah.nama')
                                        ->label('Sekolah')
                                        ->visible(fn ($record) => $record->sekolah_id !== null)
                                        ->icon('heroicon-o-academic-cap')
                                        ->weight(FontWeight::Medium)
                                        ->badge()
                                        ->color('info'),

                                    TextEntry::make('vendor.nama')
                                        ->label('Vendor')
                                        ->visible(fn ($record) => $record->vendor_id !== null)
                                        ->icon('heroicon-o-building-storefront')
                                        ->weight(FontWeight::Medium)
                                        ->badge()
                                        ->color('warning'),

                                    TextEntry::make('department')
                                        ->label('Departemen')
                                        ->icon('heroicon-o-building-library')
                                        ->badge()
                                        ->color('info'),

                                    TextEntry::make('position')
                                        ->label('Jabatan')
                                        ->icon('heroicon-o-briefcase')
                                        ->badge()
                                        ->color('info'),
                                ]),
                        ]),

                    Tabs\Tab::make('Detail Akun')
                        ->icon('heroicon-o-cog')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextEntry::make('role')
                                        ->label('Peran')
                                        ->formatStateUsing(fn (string $state): string => ucfirst($state))
                                        ->icon('heroicon-o-identification')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'superadmin' => 'danger',
                                            'admin' => 'warning',
                                            'user' => 'success',
                                            default => 'gray',
                                        }),

                                    IconEntry::make('status')
                                        ->label('Status Akun')
                                        ->icon(fn (bool $state): string => match ($state) {
                                            true => 'heroicon-o-check-circle',
                                            false => 'heroicon-o-x-circle',
                                        })
                                        ->color(fn (bool $state): string => match ($state) {
                                            true => 'success',
                                            false => 'danger',
                                        }),

                                    TextEntry::make('created_at')
                                        ->label('Tanggal Dibuat')
                                        ->dateTime('d M Y H:i')
                                        ->icon('heroicon-o-calendar')
                                        ->badge()
                                        ->color('gray'),

                                    TextEntry::make('last_login_at')
                                        ->label('Login Terakhir')
                                        ->dateTime('d M Y H:i')
                                        ->icon('heroicon-o-clock')
                                        ->badge()
                                        ->color('gray'),
                                ]),
                        ]),

                    Tabs\Tab::make('Riwayat Aktivitas')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            TextEntry::make('activity_log')
                                ->label('Aktivitas Terbaru')
                                ->listWithLineBreaks()
                                ->bulleted(),
                        ]),
                ])
                ->activeTab(0) // Changed to use index instead of string
                ->extraAttributes(['class' => 'bg-white shadow-sm rounded-xl']),
        ])
        ->columns(1);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            if ($user && $user->hasRole('admin_sekolah') || $user->hasRole('staff_sekolah')) {
                // If user is 'admin' or 'sekolah', filter by their sekolah_id
                $query->where('sekolah_id', $user->sekolah_id);
            } elseif ($user && $user->hasRole('vendor')) {
                // If user is 'vendor', filter by their vendor_id
                $query->where('vendor_id', $user->vendor_id);
            }
        }

        return $query;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = Auth::user();
        assert($user instanceof User);
        return $user->hasRole('super_admin') || $user->hasRole('staff') || $user->hasRole('admin_sekolah') ? true : false;
    }
}
