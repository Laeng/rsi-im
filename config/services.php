<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
     * I have hidden the detailed address to protect the CIG's endpoint.
     * And I seek your understanding as this is a decision for your account and their security.
     * Thank you.
     *
     * - laeng -
     */
    'rsi' => [
        'api' => [
          'salt' => env('RSI_API_SALT', '')
        ],
        'header' => [
            'one' => [
                'title' => env('RSI_HEADER_ONE_TITLE', ''),
                'length' => env('RSI_HEADER_ONE_LENGTH', ''),
            ],
            'two' => [
                'title' => env('RSI_HEADER_TWO_TITLE', ''),
                'length' => env('RSI_HEADER_TWO_LENGTH', ''),
            ],
            'three' => [
                'title' => env('RSI_HEADER_three_TITLE', ''),
                'length' => env('RSI_HEADER_three_LENGTH', ''),
            ],
        ],
        'main' => [
            'status' => env('RSI_MAIN_STATUS', ''),
            'login' => env('RSI_MAIN_LOGIN', ''),
            'captcha' => env('RSI_MAIN_CAPTCHA', ''),
            'multi-factor'=>env('RSI_MAIN_FACTOR', ''),
            'games' => env('RSI_MAIN_GAMES', ''),
            'library' => env('RSI_MAIN_LIBRARY', '')
        ],
        'spectrum' => [
            'auth' => env('RSI_SPECTRUM_AUTH', '')
        ]
    ]

];
