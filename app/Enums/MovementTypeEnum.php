<?php

namespace App\Enums;

enum MovementTypeEnum: string
{
    case Deposit = "deposit";
    case Exchange = "exchange";

    public function label(): string
    {
        return match ($this) {
            self::Deposit => __('Deposit'),
            self::Exchange => __('Exchange'),
        };
    }
}
