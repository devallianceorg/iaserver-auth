<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Authentication Social
    |--------------------------------------------------------------------------
    */
    'facebook' => [
        'client_id'     => env('FACEBOOK_ID', 'client_id'),
        'client_secret' => env('FACEBOOK_SECRET', 'client_secret'),
        'redirect'      => env('FACEBOOK_CALLBACK', 'callback')
    ],
    'google' => [
        'client_id'     => env('GOOGLE_ID', 'client_id'),
        'client_secret' => env('GOOGLE_SECRET', 'client_secret'),
        'redirect'      => env('GOOGLE_CALLBACK', 'callback')
    ],
];