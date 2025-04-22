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
    'client_service' => [
        'url' => env('CLIENT_SERVICE_URL'),
    ],

    'service_catalog_service' => [
        'url' => env('SERVICE_CATALOG_SERVICE_URL'),
    ],

    'order_service' => [
        'url' => env('ORDER_SERVICE_URL'),
    ],

    'schedule_service' => [
        'url' => env('SCHEDULE_SERVICE_URL'),
    ],

    'photography_service' => [
        'url' => env('PHOTOGRAPHY_SERVICE_URL'),
    ],

    'portfolio_service' => [
        'url' => env('PORTFOLIO_SERVICE_URL'),
    ],

];
