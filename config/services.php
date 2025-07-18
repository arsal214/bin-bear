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


    // Config: Add to config/services.php

    'jobber' => [
        'client_id' => env('JOBBER_CLIENT_ID'),
        'client_secret' => env('JOBBER_CLIENT_SECRET'),
        'redirect_uri' => env('JOBBER_REDIRECT_URI'),
        'api_version' => env('JOBBER_API_VERSION', '2023-08-18'), // Updated version
        'webhook_secret' => env('JOBBER_WEBHOOK_SECRET'),
        'base_url' => 'https://api.getjobber.com/api/graphql',
    ],

];
