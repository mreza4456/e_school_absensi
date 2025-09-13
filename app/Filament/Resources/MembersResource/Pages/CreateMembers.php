<?php

namespace App\Filament\Resources\MembersResource\Pages;

use App\Filament\Resources\MembersResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMembers extends CreateRecord
{
    protected static string $resource = MembersResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
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
}
