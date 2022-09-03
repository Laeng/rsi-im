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
                'key' => env('RSI_HEADER_ONE_KEY', ''),
                'length' => env('RSI_HEADER_ONE_LENGTH', ''),
            ],
            'two' => [
                'key' => env('RSI_HEADER_TWO_KEY', ''),
                'length' => env('RSI_HEADER_TWO_LENGTH', ''),
            ]
        ],
        'main' => [
            'status' => env('RSI_MAIN_STATUS', ''),
            'sign-in' => env('RSI_MAIN_SIGN_IN', ''),
            'captcha' => env('RSI_MAIN_CAPTCHA', ''),
            'multi-factor'=>env('RSI_MAIN_FACTOR', ''),
            'games' => env('RSI_MAIN_GAMES', ''),
            'library' => env('RSI_MAIN_LIBRARY', ''),
            'release' => env('RSI_MAIN_RELEASE', ''),
            'sign-out' => env('RSI_MAIN_SIGN_OUT', ''),
            'check' => env('RSI_MAIN_SIGN_CHECK', '')
        ],
        'spectrum' => [
            'auth' => env('RSI_SPECTRUM_AUTH', '')
        ],
        'attribute-redacted' => [
            '00' => env('RSI_ATTRIBUTE_REDACTED_00', ''),
            '01' => env('RSI_ATTRIBUTE_REDACTED_01', ''),
            '02' => env('RSI_ATTRIBUTE_REDACTED_02', ''),
            '03' => env('RSI_ATTRIBUTE_REDACTED_03', ''),
            '04' => env('RSI_ATTRIBUTE_REDACTED_04', ''),
            '05' => env('RSI_ATTRIBUTE_REDACTED_05', ''),
            '06' => env('RSI_ATTRIBUTE_REDACTED_06', ''),
            '07' => env('RSI_ATTRIBUTE_REDACTED_07', ''),
            '08' => env('RSI_ATTRIBUTE_REDACTED_08', ''),
            '09' => env('RSI_ATTRIBUTE_REDACTED_09', ''),
            '10' => env('RSI_ATTRIBUTE_REDACTED_10', ''),
            '11' => env('RSI_ATTRIBUTE_REDACTED_11', ''),

        ]
    ]

];
