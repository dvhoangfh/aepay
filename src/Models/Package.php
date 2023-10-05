<?php

namespace Dvhoangfh\Aepay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    
    public const STATUS_ACTIVE = 1;
    public const STATUS_DEACTIVATE = 0;
    
    protected $table = 'packages';
    protected $fillable = [
        'name',
        'amount',
        'type',
        'value',
        'feature',
        'status',
        'is_recommend',
        'description',
        'paddle_id',
        'billing_type',
        'billing_period',
        'trial_days',
        'raw_data',
        'package_hash_id',
    ];
    
    protected $casts = [
        'raw_data'       => 'array',
        'billing_period' => 'integer'
    ];
}
