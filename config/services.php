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
    |--------------------------------------------------------------------------
    | Google OAuth
    |--------------------------------------------------------------------------
    |
    | Credentials for Google OAuth authentication. Enable Google login
    | by setting GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in your .env
    | or configure in admin settings.
    |
    */
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI', env('APP_URL') . '/auth/google/callback'),
        'enabled' => env('GOOGLE_AUTH_ENABLED', !empty(env('GOOGLE_CLIENT_ID')) && !empty(env('GOOGLE_CLIENT_SECRET'))),
    ],

    /*
    |--------------------------------------------------------------------------
    | TurboSMTP
    |--------------------------------------------------------------------------
    |
    | Credentials for TurboSMTP email delivery service.
    | Configure in admin settings or .env file.
    |
    */
    'turbosmtp' => [
        'server' => env('TURBOSMTP_SERVER', 'pro.turbo-smtp.com'),
        'port' => env('TURBOSMTP_PORT', 587),
        'username' => env('TURBOSMTP_USERNAME'),
        'password' => env('TURBOSMTP_PASSWORD'),
        'from_address' => env('TURBOSMTP_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')),
        'from_name' => env('TURBOSMTP_FROM_NAME', env('MAIL_FROM_NAME')),
        'enabled' => env('TURBOSMTP_ENABLED', !empty(env('TURBOSMTP_USERNAME')) && !empty(env('TURBOSMTP_PASSWORD'))),
    ],

];
