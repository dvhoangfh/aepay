<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use App\Events\BytePayOrderCreated;
use Illuminate\Routing\Controller;
use Dvhoangfh\Aepay\Models\BytePayOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WebhookBytePayController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        Log::channel('log-webhook-bytepay')->info('Bytepay Webhook---' . json_encode($payload));
        if (!empty($payload['orderId'])) {
            $order = BytePayOrder::with(['package'])->where('order_id', $payload['orderId'])->first();
            if ($order) {
                $statusMapping = [
                    'PENDING' => 'PENDING',
                    'CANCEL'  => 'CANCEL',
                    'SUCCESS' => 'ACTIVE',
                    'FAIL'    => 'FAIL'
                ];
                $order->status = $statusMapping[$payload['status']] ?? '';
                $order->transaction_id = $payload['transId'] ?? '';
                if ($order->package) {
                    $extraDaysMapping = [
                        '1d' => 1,
                        '5d' => 5,
                        '1m' => 30,
                        '3m' => 90,
                        '1y' => 365,
                    ];
                    $extraDay = $extraDaysMapping[$order->package->package_hash_id] ?? 0;
                    $now = Carbon::now();
                    $endAt = Carbon::now()->addDays($extraDay);
                    $order->start_at = $now;
                    $order->ends_at = $endAt;
                }
                $order->save();
                BytePayOrderCreated::dispatch($order->order_id);
            }
        }
        return new Response();
    }
}
