<?php

namespace Dvhoangfh\Aepay\Services;

use Dvhoangfh\Aepay\Models\Package;

class PackageService
{
    public function getListActive($columns = ['*'])
    {
        return Package::where('status', Package::STATUS_ACTIVE)->select($columns)->get();
    }
    
    public function findByPaddleId($paddleId)
    {
        return Package::where('paddle_id', $paddleId)->first();
    }
    
    public static function getDay($hashId): int
    {
        switch ($hashId) {
            case '1d':
                $extraDay = 1;
                break;
            case '5d':
                $extraDay = 5;
                break;
            case '1m':
                $extraDay = 30;
                break;
            case '3m':
                $extraDay = 90;
                break;
            case '1y':
                $extraDay = 365;
                break;
            default:
                $extraDay = 0;
                break;
        }
        return $extraDay;
    }
    
    public function getPerValue($amount, $hashId): float
    {
        switch ($hashId) {
            case '1d':
            case '5d':
            case '1m':
                return round((int)$amount / self::getDay($hashId), 2);
            case '3m':
                return round((int)$amount / 3, 2);
            case '1y':
                return round((int)$amount / 12, 2);
            default:
                return 0;
        }
    }
    
    public function getSave($hashId): int
    {
        switch ($hashId) {
            case '3m':
                return 50;
            case '1y':
                return 60;
            default:
                return 0;
        }
    }
    
    public static function getPackageName($hashId): int
    {
        switch ($hashId) {
            case '1m':
                $package = '1 Month';
                break;
            case '3m':
                $package = '3 Months';
                break;
            case '1y':
                $package = '1 Year';
                break;
            case '1d':
                $package = '1 Day';
                break;
            case '5d':
                $package = '5 Days';
                break;
            default:
                $package = '';
        }
        return $package;
    }
}
