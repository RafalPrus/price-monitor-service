<?php

use App\Models\Offer;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('fake_data', function () {
    Artisan::call('db:seed', [
        '--class' => \Database\Seeders\FakeUserSeeder::class,
    ]);

    Artisan::call('db:seed', [
        '--class' => \Database\Seeders\FakeOffersSeeder::class,
    ]);
})->purpose('Add fake data');

Artisan::command('account_with_offers', function () {
    // $user = User::factory()->create([
    //     'email_verified_at' => now(),
    // ]);

    User::where('email', 'rr@rr.pl')->update([
        'email_verified_at' => now(),
    ]);

//    $exampleLinks = [
//        'https://allegro.pl/oferta/kawa-ziarnista-brazilliana-toucano-1kg-swiezo-palona-100-arabica-11119122614?bi_s=ads_premium&bi_m=mainpage:showcase:desktop&referrer=proxy&emission_unit_id=28bc9256-446a-463c-8fac-6d2f9824ee26',
//        'https://allegro.pl/oferta/zipro-mata-treningowa-180-cm-x-60-cm-x-1-5-cm-czarna-15062845408',
//        'https://eu.wrangler.com/pl-pl/shop/mezczyzni/denim/redding-in-rockstar-112350852.html?merchCategory=c030000',
//        'https://eu.wrangler.com/pl-pl/shop/mezczyzni/denim/texas-medium-stretch-in-rustic-112352715.html?merchCategory=c030000',
//    ];
//
//    foreach ($exampleLinks as $offerLink) {
//        Offer::factory()->create([
//            'user_id' => $user->id,
//            'url' => $offerLink,
//            'is_active' => 1,
//        ]);
//    }
})->purpose('testing purpose');





