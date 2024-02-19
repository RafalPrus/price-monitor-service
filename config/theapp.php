<?php

return [
    'allegro' => [
        'client_id' => env('ALLEGRO_CLIENT_ID'),
        'client_secret' => env('ALLEGRO_CLIENT_SECRET'),
        'data_provider' => [
            'price_location' => [
                'class_name' => ".mli8_k4.msa3_z4.mqu1_1.mp0t_ji.m9qz_yo.mgmw_qw.mgn2_27.mgn2_30_s.mpof_vs.munh_8.mp4t_4",
            ],
            'api_provider' => 'https://proxy.scrapeops.io/v1/',
            'api_key' => env('ALLEGRO_API_PROVIDER_KEY'),
        ],
        
    ],
    'wrangler' => [
        'data_provider' => [
            'price_location' => [
                'class_name' => ".sales",
            ],
            'api_provider' => 'https://proxy.scrapeops.io/v1/',
            'api_key' => env('WRANGLER_API_PROVIDER_KEY'),
        ],
    ],
    'checker_services' => [
        App\Services\Allegro\AllegroOfferCheckerAdapter::class,
        App\Services\Wrangler\WranglerOfferCheckerAdapter::class,
    ],
    'browsershot' => [
        'chromium' => [
            'host_ip' => gethostbyname(env('BROWSER_SHOT_CHROMIUM_HOSTNAME')),
        ],
    ],
];
