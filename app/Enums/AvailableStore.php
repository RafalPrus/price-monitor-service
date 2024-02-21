<?php

namespace App\Enums;

use App\Services\Allegro\AllegroOfferCheckerAdapter;
use App\Services\Wrangler\WranglerOfferCheckerAdapter;
use App\Traits\ValuesToArrayTrait;

enum AvailableStore: string
{
    use ValuesToArrayTrait;
    case ALLEGRO = AllegroOfferCheckerAdapter::DOMAIN;
    case WRANGLER = WranglerOfferCheckerAdapter::DOMAIN;
}
