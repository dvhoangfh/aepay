<?php

namespace Dvhoangfh\Aepay\Models;

use Illuminate\Database\Eloquent\Model;

class WordpressOrder extends Model
{
    protected $table = 'wordpress_orders';
    
    protected $dates = ['start_at', 'ends_at'];
    
    protected $casts = [
        'is_send_notify' => 'boolean',
    ];
    
    protected $fillable = [
        'order_id',
        'package_id',
        'customer_id',
        'transaction_id',
        'status',
        'start_at',
        'ends_at',
        'site',
        'amount',
        'payment_type',
        'is_send_notify',
    ];
    
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
    
    public function package()
    {
        return $this->hasOne(Package::class, 'id', 'package_id');
    }
}
