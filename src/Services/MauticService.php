<?php

namespace Dvhoangfh\Aepay\Services;

use Illuminate\Support\Facades\Log;
use Mautic\Api\Api;
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

class MauticService extends BaseService
{
    public Api $request;

    public function __construct()
    {
        $settings = [
            'userName' => config('services.mautic.username'),
            'password' => config('services.mautic.password'),
        ];
        $initAuth = new ApiAuth();
        $auth = $initAuth->newAuth($settings, 'BasicAuth');
        $api = new MauticApi();
        $this->request = $api->newApi('contacts', $auth, config('services.mautic.endpoint'));
    }

    public function updateContact($id, array $parameters, $createIfNotExists = false)
    {
        try {
            return $this->request->edit($id, $parameters, $createIfNotExists);
        } catch (\Exception $exception) {
            Log::error('Call mautic edit contact error ' . $exception->getMessage() . $exception->getTraceAsString());
        }
    }
}
