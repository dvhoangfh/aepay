<?php

namespace Dvhoangfh\Aepay\Models;

use Illuminate\Database\Eloquent\Model;

class PaypalSubscription extends Model
{
    protected $table = 'paypal_subscriptions';
    
    protected $dates = ['ends_at', 'next_billing_at'];
    
    protected $fillable = [
        'customer_id',
        'plan_id',
        'status',
        'start_at',
        'paused_from',
        'ends_at',
        'raw_data',
        'plan_name',
        'plan_amount',
        'subscription_id',
        'package_id',
        'payer_id',
        'payer_email',
        'payment_id',
        'hash_id',
        'is_renew',
        'transaction_id',
        'next_billing_at',
        'price',
        'is_send_mail_3d',
        'is_one_time',
        'site',
        'is_send_telegram'
    ];
    
    public const SUBSCRIPTION_CREATED = 'BILLING.SUBSCRIPTION.CREATED';
    public const SUBSCRIPTION_ACTIVATED = 'BILLING.SUBSCRIPTION.ACTIVATED';
    public const SUBSCRIPTION_UPDATED = 'BILLING.SUBSCRIPTION.UPDATED';
    public const SUBSCRIPTION_EXPIRED = 'BILLING.SUBSCRIPTION.EXPIRED';
    public const SUBSCRIPTION_CANCELLED = 'BILLING.SUBSCRIPTION.CANCELLED';
    public const SUBSCRIPTION_SUSPENDED = 'BILLING.SUBSCRIPTION.SUSPENDED';
    public const SUBSCRIPTION_FAILED = 'BILLING.SUBSCRIPTION.PAYMENT.FAILED';
    
    public const REFUNDED = 'PAYMENT.SALE.REFUNDED';
    public const COMPLETED = 'PAYMENT.SALE.COMPLETED';
    public const REVERSED = 'PAYMENT.SALE.REVERSED';
    
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    
    public function voucherValid()
    {
        return $this->hasOne(Voucher::class, 'code', 'voucher');
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id');
    }
}
