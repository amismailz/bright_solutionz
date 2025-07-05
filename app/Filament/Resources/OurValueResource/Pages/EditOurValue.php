<?php

namespace App\Filament\Resources\OurValueResource\Pages;

use App\Filament\Resources\OurValueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOurValue extends EditRecord
{
    protected static string $resource = OurValueResource::class;

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
