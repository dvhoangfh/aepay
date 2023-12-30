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
        }
        return \response()->json($payload);
    }
}
