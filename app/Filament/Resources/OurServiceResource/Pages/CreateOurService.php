<?php

namespace App\Filament\Resources\OurServiceResource\Pages;

use App\Filament\Resources\OurServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOurService extends CreateRecord
{
    protected static string $resource = OurServiceResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
