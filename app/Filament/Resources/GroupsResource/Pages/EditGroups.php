<?php

namespace App\Filament\Resources\GroupsResource\Pages;

use App\Filament\Resources\GroupsResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditGroups extends EditRecord
{
    protected static string $resource = GroupsResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();
        assert($user instanceof User);

        if (!$user->hasRole('super_admin')) {
            if ($user-> hasRole('admin_organization') && $user->organization_id) {
                $data['organization_id'] = $user->organization_id;
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
