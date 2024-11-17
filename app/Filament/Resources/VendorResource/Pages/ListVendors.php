<?php

namespace App\Filament\Resources\VendorResource\Pages;

use App\Filament\Resources\VendorResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListVendors extends ListRecords
{
    protected static string $resource = VendorResource::class;

    public function mount(): void
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        if ($user->hasRole('vendor')) {
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
            Actions\CreateAction::make()->label('Tambah'),
        ];
    }
}
