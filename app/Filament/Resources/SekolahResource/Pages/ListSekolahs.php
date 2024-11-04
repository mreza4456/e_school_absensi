<?php

namespace App\Filament\Resources\SekolahResource\Pages;

use App\Filament\Resources\SekolahResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListSekolahs extends ListRecords
{
    protected static string $resource = SekolahResource::class;

    public function mount(): void
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        if ($user->hasRole('admin_sekolah') || $user->hasRole('sekolah')) {
            Notification::make()
                ->warning()
                ->title('Akses Ditolak')
                ->body('Anda tidak memiliki akses ke halaman ini')
                ->send();

            $this->redirect('/admin');
            return;
        }

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
