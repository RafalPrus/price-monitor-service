<?php

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




