<?php

namespace Dvhoangfh\Aepay\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'paddle/*',
        'paypal/*',
        'bytepay/*',
        'sellix/*',
        'api/charge',
        'api/handle_log',
        'api/paylink-bytepay',
        'api/create-subscription-sellix',
    ];
}
