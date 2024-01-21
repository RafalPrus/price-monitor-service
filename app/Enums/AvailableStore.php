<?php

namespace App\Enums;

enum AvailableStore: string
{
    case ALLEGRO = 'allegro.pl';

    public static function getAvailableStores(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
