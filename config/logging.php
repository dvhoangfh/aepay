<?php
return [
    'log-webhook'        => [
        'driver'     => 'daily',
        'path'       => storage_path('log-webhook/laravel.log'),
        'level'      => 'info',
        'days'       => 60,
        'permission' => 0777,
    ],
    'log-webhook-paypal' => [
        'driver'     => 'daily',
        'path'       => storage_path('log-webhook-paypal/laravel.log'),
        'level'      => 'info',
        'days'       => 60,
        'permission' => 0777,
    ],
    'log-webhook-bytepay' => [
        'driver'     => 'daily',
        'path'       => storage_path('log-webhook-bytepay/laravel.log'),
        'level'      => 'info',
        'days'       => 60,
        'permission' => 0777,
    ],
];