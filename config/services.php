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
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],
    
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    
    'ses'              => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'facebook'         => [
        'client_id'     => env('FACEBOOK_CLIENT_ID', '2730270747017305'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', 'ec6e96422069b6c6a5c0fe22209ae0f5'),
        'redirect'      => env('FACEBOOK_REDIRECT_URL', 'https://bongro.tv/auth/facebook/callback'),
    ],
    'google'           => [
        'client_id'     => env('GOOGLE_CLIENT_ID', '633817086001-5kn0ijhtt0ocvggkfen5gvcisfa0ar8t.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', '-xi4s-pR3lDwUmGVquJup4dm'),
        'redirect'      => env('GOOGLE_REDIRECT_URL', 'https://042f116eba72.ngrok.io/auth/google/callback'),
    ],
    'bunny'            => [
        'url' => env('BUNNY_URL', 'https://aisport68.b-cdn.net'),
    ],
    'paddle'           => [
        'vendor_id'        => env('PADDLE_VENDOR_ID'),
        'vendor_auth_code' => env('PADDLE_VENDOR_AUTH_CODE')
    ],
    'mautic'           => [
        'username' => env('MAUTIC_USERNAME', 'aweclubadmin'),
        'password' => env('MAUTIC_PASSWORD', 'wqz4pew5nyc3tgp!EFW'),
        'endpoint' => env('MAUTIC_ENDPOINT', 'https://mkt.aewclub.com'),
    ],
    'bytepay'          => [
        'partner_id' => env('BYTEPAY_PARTNER_ID', 'xQ9n3KcmqE'),
        'endpoint'   => env('BYTEPAY_ENDPOINT', 'https://apipw.bytepay.vn'),
    ],
    'telegram-bot-api' => [
        'token'   => env('TELEGRAM_BOT_TOKEN', 'YOUR BOT TOKEN HERE'),
        'chat_id' => env('TELEGRAM_CHAT_ID', 'YOUR BOT TOKEN HERE'),
    ],
    'sellix' => [
        'key'   => env('SELLIX_KEY', 'Q314XfKPgQNg663zLkB3c1oCSuYgTuDNEEtyvXkc3nDP8a4J54LvrfreO60uxnZa'),
        'merchant' => env('SELLIX_MERCHANT', 'aistock'),
    ],
];
