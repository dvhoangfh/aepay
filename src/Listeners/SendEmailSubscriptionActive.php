<?php

namespace Dvhoangfh\Aepay\Listeners;

use Dvhoangfh\Aepay\Events\PaypalSubscriptionActive;
use Dvhoangfh\Aepay\Models\PaypalSubscription;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

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
            $amount = $data['resource']['billing_info']['last_payment']['amount']['value'] ?? '';
            
            try {
                $telegram = new Api(config('services.telegram-bot-api.token'));
                $data = [
                    'service'         => 'Paypal',
                    'subscription_id' => $id,
                    'package'         => $package,
                    'email'           => $subscription->customer->email,
                    'amount'          => $amount,
                    'account'         => $subscription->payment->name,
                ];
                $telegram->sendMessage(
                    [
                        'chat_id'    => config('services.telegram-bot-api.chat_id'),
                        'parse_mode' => 'HTML',
                        'text'       => "<strong>App name: </strong>" . "Aesport TV" . "\n" .
                            "<strong>Via : </strong>" . $data['service'] . "\n" .
                            "<strong>Subscription ID : </strong>" . $data['subscription_id'] . "\n" .
                            "<strong>Account : </strong>" . $data['account'] . "\n" .
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
    }
    
    public function shouldQueue(PaypalSubscriptionActive $event): bool
    {
        return $event->order->subtotal >= 5000;
    }
}
