<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $user = User::create($data);

        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return $user;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->points = $data['points'] ?? [];
        unset($data['points']);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }


        return $data;
    }

    protected function afterCreate(): void
    {
        if (!empty($this->points)) {
            $this->record->points()->sync($this->points);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
