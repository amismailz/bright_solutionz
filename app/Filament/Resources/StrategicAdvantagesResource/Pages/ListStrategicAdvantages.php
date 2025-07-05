<?php

namespace App\Filament\Resources\StrategicAdvantagesResource\Pages;

use App\Filament\Resources\StrategicAdvantagesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStrategicAdvantages extends ListRecords
{
    protected static string $resource = StrategicAdvantagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
