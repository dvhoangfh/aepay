<?php

namespace Dvhoangfh\Aepay\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Dvhoangfh\Aepay\Services\Auth\User as Authenticatable;

/**
 * @property string $email
 */
class Customer extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'password',
        'status',
        'sellix_id',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public const STATUS_ACTIVE = 1;
    
    public function paypalSubscriptionActive(): HasOne
    {
        return $this->hasOne(PaypalSubscription::class, 'customer_id', 'id')->where('status', 'ACTIVE')->where('ends_at', '>', now());
    }
    
    public function paypalSubscriptionCancelled(): HasOne
    {
        return $this->hasOne(PaypalSubscription::class, 'customer_id', 'id')->where('status', 'CANCELLED')->where('ends_at', '>', now());;
    }
    
    public function isPremiumPaypal()
    {
        $subActive = $this->paypalSubscriptionActive;
        $subCancelled = $this->paypalSubscriptionCancelled;
        
        return $subActive || $subCancelled;
    }
    
    public function getStatus()
    {
        if ($this->subscribed(Subscription::PLAN_NAME)) {
            $status = 'Premium';
            if ($this->onTrial(Subscription::PLAN_NAME)) {
                $status .= ' (trial)';
            }
        } else {
            $status = 'Free';
        }
        
        return $status;
    }
    
    public function getIsPremiumAttribute(): bool
    {
        $superUsers = ['dvhoangfh1@gmail.com', 'ducbh198@gmail.com', 'stronger.digi40@gmail.com', 'liemtt91@gmail.com'];
        if (in_array($this->email, $superUsers) || $this->isPremiumPaypal() || $this->isTrial()) {
            return true;
        }
        
        return false;
    }
}
