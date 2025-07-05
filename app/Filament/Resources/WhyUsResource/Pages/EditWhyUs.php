<?php

namespace App\Filament\Resources\WhyUsResource\Pages;

use App\Filament\Resources\WhyUsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhyUs extends EditRecord
{
    protected static string $resource = WhyUsResource::class;

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
