<?php

namespace App\Filament\Resources\OurServiceResource\Pages;

use App\Filament\Resources\OurServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOurServices extends ListRecords
{
    protected static string $resource = OurServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
