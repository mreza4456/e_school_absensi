<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
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
            if ($user-> hasRole('admin_organization') && $user->organization_id) {
                $data['organization_id'] = $user->organization_id;
                $data['vendor_id'] = null;
            } elseif ($user->vendor_id) {
                $data['vendor_id'] = $user->vendor_id;
                $data['organization_id'] = null;
            }
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();
        assert($user instanceof User);

        // Get the selected roles as array
        $selectedRoles = isset($data['roles']) ? (array)$data['roles'] : [];

        // Make sure all existing roles are preserved
        if ($this->record) {
            $existingRoles = $this->record->roles()->pluck('id')->toArray();
            $selectedRoles = array_unique(array_merge($selectedRoles, $existingRoles));
        }

        // Update the roles data
        $data['roles'] = $selectedRoles;

        // Handle organization assignment
        if (!$user->hasRole('super_admin')) {
            if ($user->hasRole('admin_organization') && $user->organization_id) {
                $data['organization_id'] = $user->organization_id;
                $data['vendor_id'] = null;
            } elseif ($user->vendor_id) {
                $data['vendor_id'] = $user->vendor_id;
                $data['organization_id'] = null;
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
