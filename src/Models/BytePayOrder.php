<?php

namespace Dvhoangfh\Aepay\Models;

use Illuminate\Database\Eloquent\Model;

class BytePayOrder extends Model
{
    protected $table = 'bytepay_orders';
    
    protected $dates = ['start_at', 'ends_at'];
    
    protected $fillable = [
        'order_id',
        'package_id',
        'customer_id',
        'transaction_id',
        'status',
        'start_at',
        'ends_at'
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
