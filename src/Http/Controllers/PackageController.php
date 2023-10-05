<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use Dvhoangfh\Aepay\Models\BytePayOrder;
use Dvhoangfh\Aepay\Models\Customer;
use Dvhoangfh\Aepay\Models\Package;
use Dvhoangfh\Aepay\Models\PaypalSubscription;
use Dvhoangfh\Aepay\Models\Subscription;
use Dvhoangfh\Aepay\Models\Voucher;
use Dvhoangfh\Aepay\Services\BytePayService;
use Dvhoangfh\Aepay\Services\CustomerService;
use Dvhoangfh\Aepay\Services\PackageService;
use Dvhoangfh\Aepay\Services\PaddleService;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class PackageController extends Controller
{
    private $packageService;
    private $customerService;
    private $paddleService;
    private BytePayService $bytePayService;
    
    public function __construct()
    {
        $this->packageService = app(PackageService::class);
        $this->customerService = app(CustomerService::class);
        $this->paddleService = app(PaddleService::class);
        $this->bytePayService = app(BytePayService::class);
    }
    
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('package.index', [
            'packages' => $this->packageService->getListActive(['id', 'name', 'amount', 'is_recommend', 'paddle_id', 'trial_days']),
            'user'     => $this->customerService->getInfo(Auth::id())
        ]);
    }
    
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPayLink(Request $request): JsonResponse
    {
        $package_id = $request->get('package_id', 0);
        $package = $this->packageService->findByPaddleId($package_id);
        $payLink = '';
        if ($package) {
            try {
                $payLink = $request->user()->newSubscription(Subscription::PLAN_NAME, $package->paddle_id)
                    ->returnTo(route('thank'))
                    ->create();
            } catch (Exception $exception) {
                Log::error('Get pay link error----' . $exception->getMessage());
            }
        }
        
        return $this->sendResponse('Success', ['url' => $payLink]);
    }
    
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getPayLinkUid(Request $request): JsonResponse
    {
        $packageId = $request->get('package_id');
        $userId = $request->get('user_id');
        if (empty($packageId) || empty($userId)) {
            return $this->sendError('Missing package or user not found');
        }
        $package = $this->packageService->findByPaddleId($packageId);
        $payLink = '';
        $customer = Customer::find($userId);
        if ($package) {
            try {
                $params = [
                    'user_id' => $customer->id,
                    'plan_id' => $package->paddle_id,
                    'type'    => 'paddle',
                ];
                $returnUrl = config('site.aisport.url') . '/thank-you?' . http_build_query($params);
                $payLink = $customer->newSubscription(Subscription::AISPORT_PLAN_NAME, $package->paddle_id)
                    ->returnTo($returnUrl)
                    ->create();
            } catch (Exception $exception) {
                Log::error('Get pay link error----' . $exception->getMessage());
                return $this->sendError('Something went wrong. Please ');
            }
        }
        
        return $this->sendResponse('Success', ['url' => $payLink]);
    }
    
    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function thank(Request $request)
    {
        $checkout = $request->get('checkout');
        if ($request->get('payment_type') === 'bp') {
            $data = $request->all();
            $data['type'] = 'credit-card';
            $urlRedirect = decrypt($data['extraData']);
            if ($urlRedirect) {
                return redirect($urlRedirect . '?' . http_build_query($data));
            }
            
            $url = "/thank-you?" . http_build_query($data);
            return view('thank-bp', ['data' => $data, 'url' => $url]);
        }
        
        return view('thank', ['data' => $this->paddleService->getCheckout($checkout)]);
    }
    
    public function charge(Request $request)
    {
        $data = $request->all();
        if (!empty($data['package_id'])) {
            $package = Package::find($data['package_id']);
            if ($package) {
                try {
                    switch ($package->package_hash_id) {
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
                            $extraDay = 6;
                            break;
                    }
                    if (!empty($data['voucher'])) {
                        $voucher = Voucher::where('code', $data['voucher'])->first();
                        if ($voucher) {
                            $extraDay += (int)$voucher->extra_day;
                        }
                    }
                    PaypalSubscription::updateOrCreate(
                        ['subscription_id' => $data['subscriptionID']],
                        [
                            'transaction_id' => $data['orderID'],
                            'customer_id'    => $data['userId'],
                            'plan_id'        => $data['paypalPlanId'],
                            'payment_id'     => $data['payment_id'],
                            'payer_email'    => $data['userEmail'],
                            'status'         => 'ACTIVE',
                            'start_at'       => Carbon::now(),
                            'ends_at'        => Carbon::now()->addDays($extraDay),
                            'hash_id'        => $package->package_hash_id,
                            'is_one_time'    => $package->is_one_time,
                            'price'          => $package->amount * $package->billing_period,
                            'raw_data'       => json_encode($data),
                        ]);
                } catch (Exception $exception) {
                    Log::error('Charge one time paypal error:--' . $exception->getMessage() . $exception->getTraceAsString());
                }
            }
        }
    }
    
    public function handleLog(Request $request)
    {
        Log::error('Paypal Approve Error : ' . json_encode($request->all()));
    }
    
    public function getPayLinkBytePay(Request $request): JsonResponse
    {
        $packageId = $request->get('package_id');
        $userId = $request->get('user_id');
        $urlRedirect = $request->get('url_redirect');
        if (empty($packageId) || empty($userId)) {
            return $this->sendError('Missing package or user not found');
        }
        $package = Package::find($packageId);
        $payLink = '';
        $customer = Customer::find($userId);
        if ($package) {
            try {
                $orderId = 'ORDER-' . time();
                $order = BytePayOrder::create([
                    'order_id'    => $orderId,
                    'package_id'  => $package->id,
                    'customer_id' => $customer->id,
                    'status'      => 'CREATED',
                ]);
                if ($order) {
                    $orderInfo = [
                        'orderId'       => $orderId,
                        'orderInfo'     => $customer->id,
                        'amount'        => floatval($package->raw_price),
                        'notifyUrl'     => route('bytepay.webhook'),
                        'redirectUrl'   => route('thank') . '?payment_type=bp',
                        'paymentMethod' => 'CC',
                        'bankCode'      => '',
                        'extraData'     => encrypt($urlRedirect),
                        'partnerId'     => config('services.bytepay.partner_id'),
                    ];
                    $query = $this->bytePayService->build_query_string($orderInfo);
                    $orderInfo['signature'] = $this->bytePayService->sign($query);
                    $orderInfo['currency'] = 'USD';
                    $result = $this->bytePayService->createPayment($orderInfo);
                    if (!empty($result['data']['paymentUrl'])) {
                        return $this->sendResponse('Success', ['url' => $result['data']['paymentUrl']]);
                    }
                }
            } catch (Exception $exception) {
                Log::error('Get pay link error----' . $exception->getMessage());
                return $this->sendError('Something went wrong. Please ');
            }
        }
        
        return $this->sendResponse('Success', ['url' => $payLink]);
    }
}
