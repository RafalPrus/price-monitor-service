<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GenerateAllegroToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-allegro-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command generating token for allegro API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        define('CLIENT_ID', config('theapp.allegro.client_id'));
        define('CLIENT_SECRET', config('theapp.allegro.client_secret'));
        define('CODE_URL', 'https://allegro.pl/auth/oauth/device');
        define('TOKEN_URL', 'https://allegro.pl/auth/oauth/token');

        $result = $this->getCode();
        echo "Otwórz ten adres w przeglądarce: \n" . $result['verification_uri_complete'] . "\n";
        $accessToken = false;
        $interval = $result['interval'];

        do {
            sleep($interval);
            $device_code = $result['device_code'];
            $resultAccessToken = $this->getAccessToken($device_code);

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

    private function getAuthorizationHeader(): array
    {
        $authorization = base64_encode(CLIENT_ID.':'.CLIENT_SECRET);
        return ["Authorization" => "Basic {$authorization}", "Content-Type" => "application/x-www-form-urlencoded"];
    }

    private function getCode(): array
    {
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

    private function getAccessToken($device_code): array
    {
        $response = Http::withHeaders($this->getAuthorizationHeader())
            ->asForm()
            ->post(TOKEN_URL, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:device_code',
                'device_code' => $device_code
            ]);

        return $response->json();
    }
}
