<?php

namespace App\Filament\Resources\WhyUsResource\Pages;

use App\Filament\Resources\WhyUsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWhyUs extends ListRecords
{
    protected static string $resource = WhyUsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
