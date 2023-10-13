<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use Dvhoangfh\Aepay\Models\SellixSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WebhookSellixController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        Log::channel('log-webhook-sellix')->info('Sellix Webhook---' . json_encode($payload));
        if (!empty($payload['event'])) {
            try {
                switch ($payload['event']) {
                    case 'subscription:created' :
                        $this->handleCreated($payload);
                        break;
                    case 'subscription:cancelled' :
                        $this->handleCancelled($payload);
                        break;
                    case 'subscription:renewed' :
                        $this->handleRenewed($payload);
                        break;
                }
            } catch (\Exception $e) {
                Log::error('SELLIX WEBHOOK ERROR --- ' . $e->getMessage() . $e->getTraceAsString());
                return new Response('Webhook Skipped');
            }
            
        }
        return new Response();
    }
    
    public function handleCreated($payload)
    {
        $invoice = $payload['data']['invoices'][0] ?? [];
        if ($invoice) {
            $subscriptionId = $invoice['uniqid'];
            $subscription = SellixSubscription::where('subscription_id', $subscriptionId)->first();
            if ($subscription) {
                $startAt = now();
                if (!empty($payload['data']['current_period_end'])) {
                    $endAt = Carbon::createFromTimestamp($payload['data']['current_period_end']);
                } else {
                    $endAt = '';
                }
                $payerEmail = $payload['data']['customer_email'] ?? '';
                $payerId = $payload['data']['customer_id'] ?? '';
                $price = $invoice['total'] ?? '';
                $currency = $invoice['currency'] ?? '';
                $subscription->update([
                    'status'      => 'ACTIVE',
                    'start_at'    => $startAt,
                    'ends_at'     => $endAt,
                    'next_billing_at',
                    'payer_email' => $payerEmail,
                    'payer_id'    => $payerId,
                    'price'       => $price,
                    'currency'    => $currency,
                ]);
            }
        }
    }
    
    public function handleCancelled($payload)
    {
    
    }
    
    public function handleRenewed($payload)
    {
    
    }
}
