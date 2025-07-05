<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class AccountDetails extends Widget
{
    protected static string $view = 'filament.widgets.account-details';

    protected static ?int $sort = 1;

    protected static ?string $placement = 'footer';

    protected int | string | array $columnSpan = 3;

    public function isVisible(): bool
    {
        return auth()->check();
    }

    protected function getViewData(): array
    {
        return [
            'user' => auth()->user(),
        ];
    }




}
