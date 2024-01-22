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

Artisan::command('allegro-token', function () {
    define('CLIENT_ID', config('theapp.allegro.client_id')); // wprowadź Client_ID aplikacji
    define('CLIENT_SECRET', config('theapp.allegro.client_secret')); // wprowadź Client_Secret aplikacji
    define('CODE_URL', 'https://allegro.pl/auth/oauth/device');
    define('TOKEN_URL', 'https://allegro.pl/auth/oauth/token');

    function getAuthorizationHeader() {
        $authorization = base64_encode(CLIENT_ID.':'.CLIENT_SECRET);
        return ["Authorization" => "Basic {$authorization}", "Content-Type" => "application/x-www-form-urlencoded"];
    }

    function getCode() {
        $authorization = base64_encode(CLIENT_ID . ':' . CLIENT_SECRET);
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $authorization,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->withOptions([
            'verify' => false
        ])->asForm()->post(CODE_URL, [
            'client_id' => CLIENT_ID
        ]);

        if (!$response->successful()) {
            exit("Something went wrong: " . $response->status() . ' ' . $response->body());
        }

        return $response->json();
    }

    function getAccessToken($device_code) {
        $response = Http::withHeaders(getAuthorizationHeader())
            ->asForm()
            ->post(TOKEN_URL, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:device_code',
                'device_code' => $device_code
            ]);

        return $response->json();
    }

    function main() {
        $result = getCode();
        echo "Użytkowniku, otwórz ten adres w przeglądarce: \n" . $result['verification_uri_complete'] . "\n";
        $accessToken = false;
        $interval = $result['interval'];

        do {
            sleep($interval);
            $device_code = $result['device_code'];
            $resultAccessToken = getAccessToken($device_code);

            if (isset($resultAccessToken['error'])) {
                if ($resultAccessToken['error'] == 'access_denied') {
                    break;
                } elseif ($resultAccessToken['error'] == 'slow_down') {
                    $interval++;
                }
            } else {
                $accessToken = $resultAccessToken['access_token'];
                echo "access_token = ", $accessToken;
            }
        } while (!$accessToken);
    }

    main();

})->purpose('Display an inspiring quote');

Artisan::command('fake_data', function () {
    Artisan::call('db:seed', [
        '--class' => \Database\Seeders\FakeUserSeeder::class,
    ]);

    Artisan::call('db:seed', [
        '--class' => \Database\Seeders\FakeOffersSeeder::class,
    ]);
})->purpose('Add fake data');




