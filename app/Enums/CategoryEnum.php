<?php

namespace App\Enums;

enum CategoryEnum: int
{
    case CLOTHES = 1;
    case ELECTRONICS = 2;
    case OTHER = 3;

    public function name(): string
    {
        return match($this) {
            self::CLOTHES => 'clothes',
            self::ELECTRONICS => 'electronics',
            self::OTHER => 'other',
        };
    }

    public static function getCategoriesIds()
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
