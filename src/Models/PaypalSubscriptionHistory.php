<?php

namespace Dvhoangfh\Aepay\Models;

use Illuminate\Database\Eloquent\Model;

class PaypalSubscriptionHistory extends Model
{
    protected $table = 'paypal_subscription_histories';
    
    protected $fillable = [
        'subscription_id',
        'type',
        'payload',
    ];
}
