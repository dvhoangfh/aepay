<?php

namespace Dvhoangfh\Aepay\Jobs;

use Dvhoangfh\Aepay\Models\Customer;
use Dvhoangfh\Aepay\Models\PaypalSubscription;
use Dvhoangfh\Aepay\Services\MauticService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EditContactMautic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $subscriptionId;

    public function __construct($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    public function handle()
    {
        $subscription = PaypalSubscription::where('subscription_id', $this->subscriptionId)->first();
        if ($subscription) {
            $mautic = app()->make(MauticService::class);
            $customer = Customer::find($subscription->customer_id);
            if ($customer && !empty($customer->mautic_id)) {
                $mautic->updateContact($customer->mautic_id, [
                    'plan'               => $this->getPlan($subscription),
                    'subscribe_date'     => $subscription->start_at,
                    'subscribe_end_date' => $subscription->ends_at,
                    'status'             => $subscription->status,
                    'type'               => $customer->isPremium ? 'vip' : 'free',
                ]);
            }
        }
    }


    public function getPlan($subscription): string
    {
        switch ($subscription->hash_id) {
            case '5d':
                return '5 days';
            case '1m':
                return '1 month';
            case '3m':
                return '3 months';
            case '1y':
                return '1 year';
            default:
                return 'unknown';
        }
    }
}
