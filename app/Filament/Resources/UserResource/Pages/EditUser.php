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

        // Tambahkan role ID 2 ke array jika belum ada
        if (!in_array(2, $roles)) {
            $roles[] = 2;
        }

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();
        assert($user instanceof User);

        // Handle image upload
        if (isset($data['image']) && $data['image'] !== $this->record->image) {
            if ($this->record->image) {
                Storage::disk('public')->delete($this->record->image);
            }
        }

        // Jika bukan superadmin, set sekolah_id atau vendor_id sesuai user yang login
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
