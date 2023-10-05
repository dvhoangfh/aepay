<?php

namespace Dvhoangfh\Aepay\Services;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class Signature
{
    public const PARAMETER_SIGNATURE = 'signature';
    
    /**
     * generate signature with hash hmac encryption
     * @param string $url
     * @param array $params
     * @return string
     */
    public function generateSignature(string $url, array $params): string
    {
        unset($params['signature']);
        ksort($params);
        $queryString = http_build_query($params);
        $content = "$url?$queryString";
        $secret = env('AI_PROVIDER_SECRET');
        if (env('APP_DEBUG') === true && App::environment('test')) {
            $array = [
                'urlGenerate' => $content,
                'hashHmac'    => hash_hmac('sha256', $content, $secret)
            ];
            Log::info('generateSignature --------------', $array);
        }
        return hash_hmac('sha256', $content, $secret);
    }
    
    /**
     * @param array $queryParameters
     * @param string $signature
     * @param string $type
     * @return bool
     */
    public function verifySignature(array $queryParameters, string $signature, string $type): bool
    {
        $secret = env('AI_PROVIDER_SECRET');
        $strToCheck = $this->parseUrlString($queryParameters, $type);
        $checkSignature = hash_hmac('sha256', $strToCheck, $secret);
        if (env('APP_DEBUG') === true && App::environment('test')) {
            $array = [
                'type'          => $type,
                'inUrl'         => $strToCheck,
                'inUrlHashHmac' => $checkSignature,
                'signature'     => $signature,
                'result'        => $checkSignature === $signature
            ];
            Log::info('VerifySignature --------------', $array);
        }
        return $checkSignature === $signature;
    }
    
    /**
     * generate url with parameter exclude
     * @param array $queryParameters
     * @param string $type
     * @return string
     */
    public function parseUrlString(array $queryParameters, string $type = 'iframe'): string
    {
        $params = [];
        switch ($type) {
            case 'iframe':
                $params = $queryParameters;
                unset($params['signature']);
                break;
        }
        ksort($params);
        return app('url')->route($type) . '?' . http_build_query($params);
    }
}
