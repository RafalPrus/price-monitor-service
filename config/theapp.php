<?php

return [
    'allegro' => [
        'client_id' => env('ALLEGRO_CLIENT_ID'),
        'client_secret' => env('ALLEGRO_CLIENT_SECRET'),
        'price' => [
            'class_name' => ".mli8_k4.msa3_z4.mqu1_1.mp0t_ji.m9qz_yo.mgmw_qw.mgn2_27.mgn2_30_s.mpof_vs.munh_8.mp4t_4",
        ]
    ],
    'checker_services' => [
        App\Services\Allegro\AllegroOfferCheckerAdapter::class,
    ]
];
