<?php

namespace Dvhoangfh\Aepay\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;
    
    protected $table = 'vouchers';
    protected $fillable = [
        'id',
        'name',
        'code',
        'start_at',
        'ends_at',
        'is_multi',
        'is_active',
        'extra_day',
        'plans',
    ];
    
    protected $attributes = [
        'valid_plans'
    ];
    
    protected $casts = [
        'is_multi'  => 'boolean',
        'is_active' => 'boolean',
    ];
}

