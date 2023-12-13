<?php

namespace Dvhoangfh\Aepay\Listeners;

use Dvhoangfh\Aepay\Events\BytePayOrderCreated;
use Dvhoangfh\Aepay\Events\OrderCreated;
use Dvhoangfh\Aepay\Models\BytePayOrder;
use Dvhoangfh\Aepay\Models\WordpressOrder;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class SendNotiOrderCreated
{
    
    public string $queue = 'subscription-event';
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function handle(OrderCreated $payload)
    {
        $order = WordpressOrder::with(['customer', 'package'])->where('order_id', $payload->payload)->first();
        try {
            $telegram = new Api(config('services.telegram-bot-api.token'));
            $data = [
                'service'  => 'WP',
                'order_id' => $payload->payload,
                'package'  => $order->package->name,
                'email'    => $order->customer->email,
                'amount'   => 0,
            ];
            $telegram->sendMessage(
                [
                    'chat_id'    => config('services.telegram-bot-api.chat_id'),
                    'parse_mode' => 'HTML',
                    'text'       => "<strong>App name: </strong>" . "Aesport TV" . "\n" .
                        "<strong>Via : </strong>" . $data['service'] . "\n" .
                        "<strong>Order ID : </strong>" . $data['order_id'] . "\n" .
                        "<strong>Package : </strong>" . $data['package'] . "\n" .
                        "<strong>Email : </strong>" . $data['email'] . "\n" .
                        "<i>Message has sent form <b>Aesport Team</b></i>"
                ]
            );
        } catch (\Exception $exception) {
            Log::error('send telegram message error' . $exception->getMessage());
        }
    }
}
