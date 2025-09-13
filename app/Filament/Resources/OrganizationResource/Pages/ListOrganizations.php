<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListOrganizations extends ListRecords
{
    protected static string $resource = OrganizationResource::class;

    public function mount(): void
    {
        $user = Auth::user();
        assert($user instanceof \App\Models\User);

        if ($user->hasRole('admin_organization') || $user->hasRole('organization')) {
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
            Actions\CreateAction::make()->label('Add'),
        ];
    }
}
