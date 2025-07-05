<?php

namespace App\Filament\Resources\WhyUsResource\Pages;

use App\Filament\Resources\WhyUsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWhyUs extends CreateRecord
{
    protected static string $resource = WhyUsResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
