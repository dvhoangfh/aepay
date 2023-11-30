<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WebhookWordpressController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $request->all();
        Log::channel('log-webhook-wordpress')->info('Wordpress webhook---' . json_encode($payload));
        return \response()->json($payload);
    }
}
