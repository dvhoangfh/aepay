<?php

namespace Dvhoangfh\Aepay\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Paddle\Cashier;

class PaddleService extends Cashier
{
    
    /**
     * Perform a Paddle API call.
     *
     * @param string $method
     * @param string $uri
     * @param array $payload
     *
     * @return Response
     */
    protected static function makeApiCall($method, $uri, array $payload = []): Response
    {
        $result = null;
        try {
            $result = parent::makeApiCall($method, $uri, $payload);
        } catch (\Exception $exception) {
            Log::error('Paddle errors-----' . $exception->getMessage() . '-----' . $exception->getTraceAsString());
        }
        return $result;
    }
    
    /**
     * @throws \JsonException
     */
    public function getPlans($uri = '/2.0/subscription/plans', $payload = [])
    {
        $result = static::makeApiCall('post', Cashier::vendorsUrl() . '/api' . $uri, $this->paddleOptions($payload));
        
        return json_decode($result, true, 512, JSON_THROW_ON_ERROR);
    }
    
    /**
     * @param string $checkout
     * @return array
     */
    public function getCheckout(string $checkout): array
    {
        return $this->getByVersion('/order?checkout_id=' . $checkout);
    }
    
    /**
     * Perform a GET Paddle API call version 1.0.
     *
     * @param string $uri
     * @param array $payload
     * @param string $version
     * @return array
     *
     */
    public function getByVersion(string $uri, array $payload = [], string $version = '1.0'): array
    {
        try {
            $url = static::checkoutUrl() . '/api/' . $version . $uri;
            $response = Http::get($url);
            
            return json_decode($response, true);
        } catch (\Exception $exception) {
            Log::error('Get order detail error---' . $exception->getMessage());
            return [];
        }
    }
}
