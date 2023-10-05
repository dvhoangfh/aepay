<?php

namespace Dvhoangfh\Aepay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;
    
    protected $table = 'payments';
    protected $fillable = [
        'id',
        'name',
        'account_name',
        'webhook',
        'client_id',
        'secret',
        'status',
        'type',
        'plans',
    ];
    
    protected $casts = [
        'status' => 'boolean',
        'plans'  => 'array',
    ];
    
    const PAYPAL = 'paypal';
    
    const PAYMENT_TEXT = [
        self::PAYPAL => 'Paypal'
    ];
}

