<?php

namespace App\Filament\Resources\StrategicAdvantagesResource\Pages;

use App\Filament\Resources\StrategicAdvantagesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStrategicAdvantages extends CreateRecord
{
    protected static string $resource = StrategicAdvantagesResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
