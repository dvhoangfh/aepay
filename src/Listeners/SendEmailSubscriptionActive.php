<?php

namespace Dvhoangfh\Aepay\Listeners;

use Dvhoangfh\Aepay\Events\PaypalSubscriptionActive;
use Dvhoangfh\Aepay\Mail\PaypalActive;
use Dvhoangfh\Aepay\Models\PaypalSubscription;
use Dvhoangfh\Aepay\Services\PackageService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailSubscriptionActive
//implements ShouldQueue
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
    
    /**
     * Handle the event.
     *
     * @param PaypalSubscriptionActive $payload
     * @return void
     */
    public function handle(PaypalSubscriptionActive $payload)
    {
        
        $data = $payload->payload;
        $id = $data['resource']['id'];
        $subscription = PaypalSubscription::with(['customer', 'payment'])->where('subscription_id', $id)->first();
        if ($subscription && !$subscription->is_send_telegram) {
            $package = PackageService::getPackageName($subscription->hash_id);
            $amount = $data['resource']['billing_info']['last_payment']['amount']['value'] ?? '';
            try {
                $dataMail = [
                    'start_time'      => $subscription->start_at,
                    'amount'          => $amount,
                    'package'         => $package,
                    'transaction_id'  => $subscription->transaction_id,
                    'subscription_id' => $id,
                    'next_billing_at' => $subscription->next_billing_at
                ];
                Mail::to($subscription->customer->email)->bcc('lotusmedia80@gmail.com')->send(new PaypalActive($dataMail));
            } catch (\Exception $exception) {
                Log::error('send email message error' . $exception->getMessage());
            }
        }
    }
    
    public function shouldQueue(PaypalSubscriptionActive $event): bool
    {
        return $event->order->subtotal >= 5000;
    }
}
