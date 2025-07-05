<?php

namespace App\Enums;

enum StatusEnum: string
{
    case Active = "active";
    case Inactive = "inactive";


    public function label(): string
    {
        return match ($this) {
            self::Active => __('Active'),
            self::Inactive => __('Inactive'),
        };
    }
}
