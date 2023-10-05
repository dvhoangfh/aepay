<?php

namespace Dvhoangfh\Aepay\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Laravel\Paddle\Events\WebhookReceived;

class SaveWebhookListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    /**
     * Handle the event.
     *
     * @param WebhookReceived $event
     * @return void
     */
    public function handle(WebhookReceived $event)
    {
        Log::channel('log-webhook')->info('Paddle webhook ' . json_encode($event));
    }
}
