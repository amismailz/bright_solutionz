<?php

namespace App\Filament\Resources\OurServiceResource\Pages;

use App\Filament\Resources\OurServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOurService extends EditRecord
{
    protected static string $resource = OurServiceResource::class;

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
