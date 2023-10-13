<?php

namespace Dvhoangfh\Aepay\Models;

use Illuminate\Database\Eloquent\Model;

class SellixSubscription extends Model
{
    protected $table = 'sellix_subscriptions';
    
    protected $dates = ['ends_at', 'next_billing_at'];
    
    protected $fillable = [
        'subscription_id',
        'package_id',
        'customer_id',
        'status',
        'start_at',
        'ends_at',
        'next_billing_at',
        'payer_email',
        'payer_id',
        'hash_id',
        'price',
        'currency',
    ];
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
