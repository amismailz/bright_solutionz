<?php

namespace App\Filament\Resources\StrategicAdvantagesResource\Pages;

use App\Filament\Resources\StrategicAdvantagesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStrategicAdvantages extends EditRecord
{
    protected static string $resource = StrategicAdvantagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
