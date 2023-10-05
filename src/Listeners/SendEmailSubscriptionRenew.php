<?php

namespace Dvhoangfh\Aepay\Listeners;

use Dvhoangfh\Aepay\Events\PaypalSubscriptionRenew;
use App\Mail\PaypalRenew;
use Dvhoangfh\Aepay\Models\PaypalSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendEmailSubscriptionRenew
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
     * @param object $event
     * @return void
     */
    public function handle(PaypalSubscriptionRenew $payload)
    {
        return true;
        $data = $payload->payload;
        $id = $data['resource']['billing_agreement_id'];
        $subscription = PaypalSubscription::with('customer')->where('subscription_id', $id)->first();
        if ($subscription) {
            $startTime = $data['resource']['create_time'] ? Carbon::parse($data['resource']['create_time']) : '';
            switch ($subscription->hash_id) {
                case '1m':
                    $package = '1 Month';
                    break;
                case '3m':
                    $package = '3 Months';
                    break;
                case '1y':
                    $package = '1 Year';
                    break;
                case '1d':
                    $package = '1 Day';
                    break;
                case '5d':
                    $package = '5 Days';
                    break;
                default:
                    $package = '';
            }
            $amount = $data['resource']['amount']['total'] ?? '';
            $nextBillingAt = $subscription->next_billing_at;
            $transactionId = $data['id'] ?? '';
            $data = [
                'start_time'      => $startTime,
                'amount'          => $amount,
                'package'         => $package,
                'transaction_id'  => $transactionId,
                'subscription_id' => $id,
                'next_billing_at' => $nextBillingAt
            ];
            Mail::to($subscription->customer->email)->send(new PaypalRenew($data));
        }
    }
}
