<?php

namespace Dvhoangfh\Aepay\Listeners;

use Carbon\Carbon;
use Dvhoangfh\Aepay\Events\OrderCreated;
use Dvhoangfh\Aepay\Mail\OrderActive;
use Dvhoangfh\Aepay\Models\WordpressOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailOrderActive implements ShouldQueue
{
    use InteractsWithQueue;
    
    public string $queue = 'default';
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function handle(OrderCreated $payload)
    {
        $order = WordpressOrder::with(['customer', 'package'])->where('order_id', $payload->payload)->first();
        if ($order) {
            $data = [
                'start_time'      => Carbon::now(),
                'amount'          => $order->amount,
                'package'         => $order->package->name,
            ];
            Mail::to($order->customer->email)->bcc('lotusmedia80@gmail.com')->send(new OrderActive($data));
        }
    }
}
