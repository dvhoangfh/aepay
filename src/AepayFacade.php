<?php

namespace Dvhoangfh\Aepay;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dvhoangfh\Aepay\Skeleton\SkeletonClass
 */
class AepayFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'aepay';
    }
}
