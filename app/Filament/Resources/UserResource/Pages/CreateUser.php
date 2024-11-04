<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        assert($user instanceof User);

        // Pastikan array roles ada
        $roles = $data['roles'] ?? [];

        // Tambahkan role ID 2 ke array
        $roles[] = 2;

        // Update data dengan roles yang baru
        $data['roles'] = $roles;

        if (!$user->hasRole('super_admin')) {
            if ($user-> hasRole('admin_sekolah') && $user->sekolah_id) {
                $data['sekolah_id'] = $user->sekolah_id;
                $data['vendor_id'] = null;
            } elseif ($user->vendor_id) {
                $data['vendor_id'] = $user->vendor_id;
                $data['sekolah_id'] = null;
            }
        }

        return $data;
    }
}
