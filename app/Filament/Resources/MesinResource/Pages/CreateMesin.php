<?php

namespace App\Filament\Resources\MesinResource\Pages;

use App\Filament\Resources\MesinResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateMesin extends CreateRecord
{
    protected static string $resource = MesinResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        assert($user instanceof User);

        if (!$user->hasRole('super_admin')) {
            if ($user-> hasRole('vendor') && $user->vendor_id) {
                $data['vendor_id'] = $user->vendor_id;
            }
        }

        return $data;
    }
}
