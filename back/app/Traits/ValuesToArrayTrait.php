<?php

namespace App\Traits;

trait ValuesToArrayTrait
{
    public static function getArrayOfValues()
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}