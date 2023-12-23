<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use Dvhoangfh\Aepay\Events\OrderCreated;
use Dvhoangfh\Aepay\Models\WordpressOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class WebhookWordpressController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        Log::channel('log-webhook-wordpress')->info('Wordpress webhook---' . json_encode($payload));
        $order = WordpressOrder::where('order_id', $payload['id'])->first();
        if ($order) {
            if (!empty($payload['status'])) {
                $order->status = $payload['status'];
            }
            $order->save();
            OrderCreated::dispatch($order->order_id);
            try {
                $telegram = new Api(config('services.telegram-bot-api.token'));
                $data = [
                    'service'  => 'WP',
                    'order_id' => $order->order_id,
                    'package'  => $order->package->name,
                    'email'    => $order->customer->email,
                    'amount'   => $order->amount,
                ];
                $telegram->sendMessage(
                    [
                        'chat_id'    => config('services.telegram-bot-api.chat_id'),
                        'parse_mode' => 'HTML',
                        'text'       => "<strong>App name: </strong>" . "Aesport TV" . "\n" .
                            "<strong>Via : </strong>" . $data['service'] . "\n" .
                            "<strong>Order ID : </strong>" . $data['order_id'] . "\n" .
                            "<strong>Package : </strong>" . $data['package'] . "\n" .
                            "<strong>Amount : </strong>" . $data['amount'] . "\n" .
                            "<strong>Email : </strong>" . $data['email'] . "\n" .
                            "<i>Message has sent form <b>Aesport Team</b></i>"
                    ]
                );
            } catch (\Exception $exception) {
                Log::error('send telegram message error' . $exception->getMessage());
            }
        }
        return \response()->json($payload);
    }
}
