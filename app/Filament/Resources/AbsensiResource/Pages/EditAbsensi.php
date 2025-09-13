<?php

namespace App\Filament\Resources\AbsensiResource\Pages;

use App\Filament\Resources\AbsensiResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditAbsensi extends EditRecord
{
    protected static string $resource = AbsensiResource::class;
   public function getTitle(): string
{
    return __('Edit Attendance');
}
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();
        assert($user instanceof User);

        if (!$user->hasRole('super_admin')) {
            if ($user->hasRole('admin_organization') || ($user->hasRole('organization') && $user->organization_id)) {
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
