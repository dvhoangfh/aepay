<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use Dvhoangfh\Aepay\Events\OrderCreated;
use Dvhoangfh\Aepay\Models\WordpressOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        }
        return \response()->json($payload);
    }
}
