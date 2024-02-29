<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use Dvhoangfh\Aepay\Models\Customer;
use Dvhoangfh\Aepay\Models\Package;
use Dvhoangfh\Aepay\Models\Payment;
use Dvhoangfh\Aepay\Models\Voucher;
use Dvhoangfh\Aepay\Services\PackageService;
use Dvhoangfh\Aepay\Services\SettingService;
use Dvhoangfh\Aepay\Services\Signature;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sellix\PhpSdk\Sellix;

class GgIframeController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->get('user_id');
        $userEmail = $request->get('user_email');
        $planId = $request->get('plan_id');
        $vouchers = Voucher::where('is_active', 1)->get();

        $data = [
            'user_id'             => $userId,
            'user_email'          => $userEmail,
            'plan_id'             => $planId,
            'client_id'           => $request->get('client_id'),
            'vouchers'            => $vouchers,
            'is_enable_paypal'    => true,
            'is_enable_bytepay'   => false,
            'is_enable_sellix'    => false,
            'is_enable_wordpress' => false,
        ];

        return view('aepay::gg-iframe', [
            'data' => $data
        ]);
    }
}