<?php

namespace Dvhoangfh\Aepay\Providers;

use Dvhoangfh\Aepay\Events\BytePayOrderCreated;
use Dvhoangfh\Aepay\Events\OrderCreated;
use Dvhoangfh\Aepay\Events\PaypalSubscriptionActive;
use Dvhoangfh\Aepay\Events\PaypalSubscriptionRenew;
use Dvhoangfh\Aepay\Listeners\SendEmailOrderActive;
use Dvhoangfh\Aepay\Listeners\SendEmailSubscriptionActive;
use Dvhoangfh\Aepay\Listeners\SendEmailSubscriptionRenew;
use Dvhoangfh\Aepay\Listeners\SendNotiOrderCreated;
use Dvhoangfh\Aepay\Listeners\SendNotiSubscriptionActive;
use Dvhoangfh\Aepay\Listeners\SendTelegramOrderCreated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;


class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaypalSubscriptionActive::class => [
            SendEmailSubscriptionActive::class,
            SendNotiSubscriptionActive::class
        ],
        PaypalSubscriptionRenew::class  => [
            SendEmailSubscriptionRenew::class
        ],
        BytePayOrderCreated::class      => [
            SendTelegramOrderCreated::class
        ],
        OrderCreated::class      => [
//            SendNotiOrderCreated::class,
            SendEmailOrderActive::class,
        ]
    ];
    
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}