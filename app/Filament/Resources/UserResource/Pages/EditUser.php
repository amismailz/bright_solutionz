<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->points = $data['points'] ?? [];
        unset($data['points']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if (!empty($this->points)) {
            $this->record->points()->sync($this->points);
        }

        if (!empty($this->data['role'])) {
            $this->record->syncRoles([$this->data['role']]);
        }

    }

    protected function fillForm(): void
    {
        $this->form->fill(
            $this->record->only([
                'name', 'email','username', 'phone', 'gender', 'association_id', 'range_id', 'status','disallow_location_track'
            ]) + [
                'role' => $this->record->getRoleNames()->first(),
                'points' => $this->record->points->pluck('id')->toArray(),
            ]
        );
    }

    protected function getRedirectUrl(): string
    {
        
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
