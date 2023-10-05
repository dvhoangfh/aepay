<?php

namespace Dvhoangfh\Aepay\Models;

use Laravel\Paddle\Subscription as CashierSubscription;

class Subscription extends CashierSubscription
{
    public const PLAN_NAME = 'ai_stock_plan';
    public const AISPORT_PLAN_NAME = 'ai_sport_plan';
    
    public function package()
    {
        return $this->hasOne(Package::class, 'paddle_id', 'paddle_plan');
    }
    //
}
