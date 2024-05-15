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

class IframeController extends Controller
{
    public function index(Request $request)
    {
        $signatureService = app()->make(Signature::class);
        $userId = $request->get('user_id');
        $paymentId = $request->get('payment_id');
        $site = $request->get('site', 'aesport');
//        $signature = $this->getSignature($request);
//        if (!$signatureService->verifySignature($request->query(), $signature, 'iframe')) {
//            return view('aepay::wrong-signature');
//        }
        $user = Customer::where('id', $userId)->select('id', 'email')->first();
        $paypal = Payment::find($paymentId);
        $packages = Package::where('status', Package::STATUS_ACTIVE)->select(['id', 'name', 'amount', 'is_recommend', 'paddle_id', 'trial_days', 'billing_period', 'billing_type', 'paypal_plan_id', 'package_hash_id', 'sellix_product_id', 'wordpress_product_id'])->get();
        foreach ($packages as &$package) {
            $package->day_value = PackageService::getDay($package->package_hash_id);
            $package->per_day = PackageService::getPerValue($package->amount * $package->billing_period, $package->package_hash_id);
            $package->save_value = PackageService::getSave($package->package_hash_id);
        }
        $vouchers = Voucher::where('is_active', 1)->get();
        $enablePaypal = app(SettingService::class)->get('enable_paypal', 'off');
        $enableBytepay = app(SettingService::class)->get('enable_bytepay', 'off');
        $enableSellix = app(SettingService::class)->get('enable_sellix', 'off');
        $enableWordpress = app(SettingService::class)->get('enable_wordpress', 'off');
        $urlRedirect = $request->get('url_redirect');
        $urlCallBack = route('thank');
        $urlBack = $request->get('url_back');
        $wordPressParam = [
            'user_id'      => $userId,
            'site'         => $site,
            'url_redirect' => $urlRedirect,
            'back'         => $urlBack
        ];
        $settings = [
            'is_enable_paypal'           => app(SettingService::class)->get('enable_paypal', 'off') === 'on',
            'is_enable_bytepay'          => app(SettingService::class)->get('enable_bytepay', 'off') === 'on',
            'is_enable_sellix'           => app(SettingService::class)->get('enable_sellix', 'off') === 'on',
            'is_enable_wordpress_paypal' => $this->getEnablePaypal(clone $user),
            'is_enable_wordpress_stripe' => app(SettingService::class)->get('enable_wordpress_stripe', 'off') === 'on',
            'is_enable_wordpress_paycec' => app(SettingService::class)->get('enable_wordpress_paycec', 'off') === 'on',
        ];
        return view('aepay::iframe', [
            'packages'            => $packages,
            'user_id'             => $userId,
            'client_id'           => $request->get('client_id'),
            'user'                => $user,
            'plans'               => $paypal->plans,
            'paymentId'           => $paymentId,
            'vouchers'            => $vouchers,
            'site'                => $site,
            'is_enable_paypal'    => $enablePaypal === 'on',
            'is_enable_bytepay'   => $enableBytepay === 'on',
            'is_enable_sellix'    => $enableSellix === 'on',
            'is_enable_wordpress' => $enableWordpress === 'on',
            'url_redirect'        => $urlRedirect,
            'url_callback'        => $urlCallBack,
            'url_back'            => $urlBack,
            'wordpressParam'      => $wordPressParam,
            'settings'            => $settings
        ]);
    }

    public function getSignature(Request $request): string
    {
        $params = $request->all();
        $signature = $params[Signature::PARAMETER_SIGNATURE] ?? '';

        return (string)$signature;
    }

    public function getEnablePaypal($user): bool
    {

        $isEnable = app(SettingService::class)->get('enable_paypal', 'off') === 'on';

        return $isEnable && $user->isMemberPremium;
    }
}