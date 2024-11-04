<?php

namespace App\Filament\Resources\KelasResource\Pages;

use App\Filament\Resources\KelasResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateKelas extends CreateRecord
{
    protected static string $resource = KelasResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        assert($user instanceof User);

        if (!$user->hasRole('super_admin')) {
            if ($user-> hasRole('admin_sekolah') && $user->sekolah_id) {
                $data['sekolah_id'] = $user->sekolah_id;
            }
        }

        return $data;
    }
}
