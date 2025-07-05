<?php

namespace App\Enums;

enum GenderEnum: string
{
    case Male = "male";
    case Female = "female";


    public function label(): string
    {
        return match ($this) {
            self::Male => __('Male'),
            self::Female => __('Female'),
        };
    }
}
