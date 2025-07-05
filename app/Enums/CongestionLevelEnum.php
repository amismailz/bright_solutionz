<?php

namespace App\Enums;

enum CongestionLevelEnum: string
{
    case High = "high";
    case Medium = "medium";
    case Low = "low";


    public function label(): string
    {
        return match ($this) {
            self::High => __('High'),
            self::Medium => __('Medium'),
            self::Low => __('Low'),
        };
    }
}
