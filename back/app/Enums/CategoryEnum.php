<?php

namespace App\Enums;
use App\Traits\ValuesToArrayTrait;

enum CategoryEnum: int
{
    use ValuesToArrayTrait;
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
}
