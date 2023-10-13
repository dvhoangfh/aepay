<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use Dvhoangfh\Aepay\Jobs\EditContactMautic;
use Illuminate\Routing\Controller;
use Dvhoangfh\Aepay\Events\PaypalSubscriptionActive;
use Dvhoangfh\Aepay\Events\PaypalSubscriptionRenew;
use Dvhoangfh\Aepay\Models\Customer;
use Dvhoangfh\Aepay\Models\Payment;
use Dvhoangfh\Aepay\Models\PaypalSubscription;
use Dvhoangfh\Aepay\Models\PaypalSubscriptionHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WebhookPayPalController extends Controller
{
    public function __construct()
    {
    
    }
    
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        Log::channel('log-webhook-paypal')->info('Paypal Webhook---' . json_encode($payload));
        $subscriptionId = $payload['resource']['id'] ?? '';
        if (empty($subscriptionId)) {
            $subscriptionId = $payload['resource']['billing_agreement_id'] ?? '';
        }
        if (!empty($payload['event_type']) && !empty($subscriptionId)) {
            try {
                switch ($payload['event_type']) {
                    case PaypalSubscription::SUBSCRIPTION_CREATED :
                        $this->handleCreated($payload);
                        break;
                    case PaypalSubscription::SUBSCRIPTION_ACTIVATED :
                        $this->handleActivated($payload);
                        break;
                    case PaypalSubscription::SUBSCRIPTION_UPDATED :
                        $this->handleUpdated($payload);
                        break;
                    case PaypalSubscription::SUBSCRIPTION_CANCELLED :
                        $this->handleCancelled($payload);
                        break;
                    case PaypalSubscription::SUBSCRIPTION_EXPIRED :
                        $this->handleExpired($payload);
                        break;
                    case PaypalSubscription::SUBSCRIPTION_SUSPENDED :
                        $this->handleSuspended($payload);
                        break;
                    case PaypalSubscription::SUBSCRIPTION_FAILED :
                        $this->handleFailed($payload);
                        break;
                    case PaypalSubscription::REFUNDED :
                        $subscriptionId = $this->handleRefunded($payload);
                        break;
                    case PaypalSubscription::COMPLETED :
                        $subscriptionId = $this->handleCompleted($payload);
                        break;
                }
                if ($payload['event_type'] !== PaypalSubscription::SUBSCRIPTION_CREATED) {
                    EditContactMautic::dispatch($subscriptionId)->onQueue('default');
                    PaypalSubscriptionHistory::create([
                        'subscription_id' => $subscriptionId,
                        'type'            => $payload['event_type'],
                        'payload'         => json_encode($payload, true)
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('PAYPAL WEBHOOK ERROR --- ' . $e->getMessage() . $e->getTraceAsString());
                return new Response('Webhook Skipped');
            }
            
        }
        return new Response();
    }
    
    public function handleCreated($data)
    {
        $subscription = PaypalSubscription::where('subscription_id', $data['resource']['id'])->first();
        $paymentId = (int)$data['resource']['subscriber']['name']['given_name'] ?? '';
        $customerEmail = $data['resource']['subscriber']['email_address'] ?? '';
        $planId = $data['resource']['plan_id'] ?? '';
        $subscriptionId = $data['resource']['id'] ?? '';
        if ($subscription) {
            if ($paymentId) {
                $subscription->payment_id = $paymentId;
            }
            $subscription->save();
        } else {
            $customer = Customer::where('email', $customerEmail)->first();
            if ($customer) {
                PaypalSubscription::create([
                    'customer_id'     => $customer->id,
                    'plan_id'         => $planId,
                    'subscription_id' => $subscriptionId,
                    'payment_id'      => $paymentId,
                    'payer_email'     => $customerEmail,
                    'status'          => 'CREATED',
                    'start_at'        => Carbon::now(),
                    'paused_from'     => null,
                    'ends_at'         => null,
                ]);
            }
        }
    }
    
    public function handleActivated($data)
    {
        $subscription = PaypalSubscription::with(['voucherValid'])->where('subscription_id', $data['resource']['id'])->first();
        $bonusDay = !empty($subscription->voucherValid->extra_day) ? (int)$subscription->voucherValid->extra_day : 1;
        $customerEmail = $data['resource']['subscriber']['email_address'] ?? '';
        $payerId = $data['resource']['subscriber']['payer_id'] ?? '';
        $paymentId = $data['resource']['subscriber']['name']['given_name'] ?? '';
        $planId = $data['resource']['plan_id'] ?? '';
        $nextBillingAt = Carbon::parse($data['resource']['billing_info']['next_billing_time']);
        $endsAt = clone $nextBillingAt;
        $endsAt = $endsAt->addDays($bonusDay);
        $status = $data['resource']['status'];
        $subscriptionId = $data['resource']['id'] ?? '';
        $transactionId = $data['id'] ?? '';
        $price = $data['resource']['billing_info']['last_payment']['amount']['value'] ?? '';
        $hashId = $this->getHashIdByPlan($planId);
        if ($subscription) {
            if (!empty($planId)) {
                $subscription->plan_id = $planId;
            }
            if (!empty($status)) {
                $subscription->status = $status;
            }
            if (!empty($endsAt)) {
                $subscription->ends_at = $endsAt;
            }
            $subscription->payer_email = $customerEmail;
            $subscription->payer_id = $payerId;
            $subscription->hash_id = $hashId;
            $subscription->transaction_id = $transactionId;
            $subscription->next_billing_at = $nextBillingAt;
            $subscription->price = $price;
            $subscription->save();
        } else {
            $now = Carbon::now();
            $customer = Customer::where('email', $customerEmail)->first();
            if ($customer) {
                PaypalSubscription::create([
                    'customer_id'     => $customer->id,
                    'plan_id'         => $planId,
                    'subscription_id' => $subscriptionId,
                    'payer_email'     => $customerEmail,
                    'payer_id'        => $payerId,
                    'payment_id'      => (int)$paymentId,
                    'hash_id'         => $hashId,
                    'status'          => $status,
                    'start_at'        => $now,
                    'paused_from'     => null,
                    'ends_at'         => $endsAt,
                    'raw_data'        => json_encode($data, true),
                    'transaction_id'  => $transactionId,
                    'next_billing_at' => $nextBillingAt,
                    'price'           => $price
                ]);
            }
        }
        PaypalSubscriptionActive::dispatch($data);
    }
    
    public function handleUpdated($data)
    {
    
    }
    
    public function handleExpired($data)
    {
    
    }
    
    public function handleCancelled($data)
    {
        $subscription = PaypalSubscription::where('subscription_id', $data['resource']['id'])->first();
        if ($subscription) {
            if (!empty($data['resource']['status'])) {
                $subscription->status = $data['resource']['status'];
            }
            if (!empty($data['resource']['billing_info']['next_billing_time'])) {
                $subscription->ends_at = Carbon::parse($data['resource']['billing_info']['next_billing_time']);
            }
            $subscription->save();
        }
    }
    
    public function handleSuspended($data)
    {
        $subscription = PaypalSubscription::where('subscription_id', $data['resource']['id'])->first();
        if ($subscription) {
            if (!empty($data['resource']['status'])) {
                $subscription->status = $data['resource']['status'];
            }
            if (!empty($data['resource']['billing_info']['next_billing_time'])) {
                $subscription->paused_from = Carbon::now();
            }
            $subscription->save();
        }
    }
    
    public function handleFailed($data)
    {
    
    }
    
    public function handleRefunded($data)
    {
        $subscriptionId = $data['resource']['invoice_number'];
        $subscription = PaypalSubscription::where('subscription_id', $subscriptionId)->first();
        if ($subscription) {
            $subscription->status = 'REFUNDED';
            $subscription->ends_at = null;
            $subscription->save();
        }
        
        return $subscriptionId;
    }
    
    public function handleCompleted($data)
    {
        $subscriptionId = $data['resource']['billing_agreement_id'];
        $subscription = PaypalSubscription::where('subscription_id', $subscriptionId)->first();
        if (!$subscription) {
            return $subscriptionId;
        }
        if (!$subscription->is_renew) {
            $subscription->is_renew = true;
            $subscription->save();
            return $subscriptionId;
        }
        if (in_array($subscription->status, ['ACTIVE', 'EXPIRED'])) {
            switch ($subscription->hash_id) {
                case '1d':
                    $extraDay = 1;
                    break;
                case '5d':
                    $extraDay = 5;
                    break;
                case '1m':
                    $extraDay = 30;
                    break;
                case '3m':
                    $extraDay = 90;
                    break;
                case '1y':
                    $extraDay = 365;
                    break;
                default:
                    $extraDay = 0;
                    break;
            }
            if (!empty($subscription->next_billing_at)) {
                $subscription->next_billing_at = $subscription->next_billing_at->addDays($extraDay);
            }
            if ($subscription->status === 'EXPIRED') {
                $subscription->ends_at = now()->addDays($extraDay);
                $subscription->status = 'ACTIVE';
            } else {
                $subscription->ends_at = $subscription->ends_at->addDays($extraDay);
            }
            $subscription->is_send_mail_3d = false;
            $subscription->save();
            PaypalSubscriptionRenew::dispatch($data);
            
            return $subscriptionId;
        }
    }
    
    public function getHashIdByPlan($planId)
    {
        $payment = Payment::where('plans', 'like', "%$planId%")->first();
        if ($payment) {
            $plans = $payment->plans;
            return array_search($planId, $plans);
        }
    }
}
