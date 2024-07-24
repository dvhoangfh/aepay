<?php

namespace Dvhoangfh\Aepay\Http\Controllers;

use Dvhoangfh\Aepay\Models\BytePayOrder;
use Dvhoangfh\Aepay\Models\Customer;
use Dvhoangfh\Aepay\Models\Package;
use Dvhoangfh\Aepay\Models\PaypalSubscription;
use Dvhoangfh\Aepay\Models\SellixSubscription;
use Dvhoangfh\Aepay\Models\Subscription;
use Dvhoangfh\Aepay\Models\Voucher;
use Dvhoangfh\Aepay\Models\WordpressOrder;
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
use Illuminate\Support\Str;
use Sellix\PhpSdk\Sellix;
use Sellix\PhpSdk\SellixException;
use Stripe\Order;
use Telegram\Bot\Api;

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
        if (!empty($request->get('woo_order_id'))) {
            $data = $request->all();
            Log::channel('log-webhook-wordpress')->info('Wordpress callback---' . json_encode($data));
            $order = WordpressOrder::with('package', 'customer')->find($data['order_id']);
            if ($order) {
                if (!empty($data['status'])) {
                    $order->status = $data['status'];
                }
                if (!empty($data['woo_order_id'])) {
                    $order->order_id = $data['woo_order_id'];
                }
                $now = now();
                $order->start_at = $now;
                $order->ends_at = $now->addDays(PackageService::getDay($order->package->package_hash_id));
                $order->save();
                if (!$order->is_send_notify) {
                    try {
                        $telegram = new Api(config('services.telegram-bot-api.token'));
                        $data = [
                            'service'  => 'WP',
                            'order_id' => $data['woo_order_id'],
                            'package'  => $order->package->name,
                            'email'    => $order->customer->email,
                            'amount'   => $order->amount,
                        ];
                        $telegram->sendMessage(
                            [
                                'chat_id'    => config('services.telegram-bot-api.chat_id'),
                                'parse_mode' => 'HTML',
                                'text'       => "<strong>App name: </strong>" . "Aesport TV" . "\n" .
                                    "<strong>Via : </strong>" . $data['service'] . "\n" .
                                    "<strong>Order ID : </strong>" . $data['order_id'] . "\n" .
                                    "<strong>Package : </strong>" . $data['package'] . "\n" .
                                    "<strong>Amount : </strong>" . $data['amount'] . "\n" .
                                    "<strong>Email : </strong>" . $data['email'] . "\n" .
                                    "<i>Message has sent form <b>Aesport Team (Callback)</b></i>"
                            ]
                        );
                        $order->is_send_notify = true;
                        $order->save();
                    } catch (\Exception $exception) {
                        Log::error('send telegram message error' . $exception->getMessage());
                    }
                }
            }
            $urlRedirect = $data['hash'] ?? '';
//            try {
//                $urlRedirect = decrypt($dataHash);
//            } catch (Exception $e) {
//                Log::error('Decrypt hash error ' . $dataHash);
//            }
            if (!$urlRedirect) {
                switch ($order->site) {
                    case '24h':
                        $urlRedirect = 'https://24sport.tv/thank-you';
                        break;
                    default:
                        $urlRedirect = 'https://aesport.tv/thank-you';
                        break;
                }
            }
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
                            'site'           => $data['site'],
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
        $site = $request->get('site');
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
                    'site'        => $site,
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

    public function createSubscriptionSellix(Request $request): JsonResponse
    {
        $client = new Sellix(config('services.sellix.key'), config('services.sellix.merchant'));
        $packageId = $request->get('package_id');
        $userId = $request->get('user_id');
        if (empty($packageId) || empty($userId)) {
            return $this->sendError('Missing package or user not found');
        }
        $package = Package::find($packageId);
        $payLink = '';
        $customer = Customer::find($userId);
        $sellixCustomerId = $customer->sellix_id;
        if (!$sellixCustomerId) {
            $customer_payload = [
                "email"   => $customer->email,
                "name"    => $customer->name,
                "surname" => $customer->name,
            ];
            try {
                $customerId = $client->create_customer($customer_payload);
                $customer->sellix_id = $customerId;
                $customer->save();
                $sellixCustomerId = $customerId;
            } catch (SellixException $e) {
                Log::error('Create sellix customer error ----' . $e->getMessage());
            }
        }
        if ($package) {
            $subscriptionPayload = [
                "product_id"    => $package->sellix_product_id,
                "coupon_code"   => null,
                "custom_fields" => [
                    "customer_id" => $customer->id
                ],
                "customer_id"   => $sellixCustomerId,
                "gateway"       => null
            ];

            try {
                $response = $client->create_subscription($subscriptionPayload);
                if (!empty($response->uniqid)) {
                    SellixSubscription::create([
                        'subscription_id' => $response->uniqid,
                        'package_id'      => $package->id,
                        'customer_id'     => $customer->id,
                        'hash_id'         => $package->package_hash_id,
                        'status'          => 'CREATED',
                    ]);
                }

                if (!empty($response->url)) {
                    return $this->sendResponse('Success', ['url' => $response->url]);
                }
            } catch (Exception $exception) {
                Log::error('Get pay link sellix error----' . $exception->getMessage());
                return $this->sendError('Something went wrong. Please ');
            }
        }

        return $this->sendResponse('Success', ['url' => $payLink]);
    }

    public function createWordpressOrder(Request $request): JsonResponse
    {
        $packageId = $request->get('package_id');
        $userId = $request->get('user_id');
        $site = $request->get('site');
        $urlRedirect = $request->get('url_redirect');
        $payment = $request->get('payment');
        $urlBack = $request->get('back');
        $isDev = Str::contains(url()->current(), 'dev');
        if (empty($packageId) || empty($userId)) {
            return $this->sendError('Missing package or user not found');
        }
        $package = Package::find($packageId);
        $customer = Customer::find($userId);
        $orderId = 'OD' . time();
        $order = WordpressOrder::create([
            'order_id'     => $orderId,
            'package_id'   => $package->id,
            'customer_id'  => $customer->id,
            'status'       => 'CREATED',
            'site'         => $site,
            'amount'       => floatval($package->raw_price),
            'payment_type' => $payment
        ]);
        $payLink = [
            'product_id' => $package->wordpress_product_id,
            'email'      => $customer->email,
            'order_id'   => $order->id,
            'callback'   => route('thank'),
            'webhook'    => route('wordpress.webhook'),
            'payment'    => $payment,
            'hash'       => $urlRedirect,
            'back'       => $urlBack
        ];
        Log::info('Payload send wp ' . json_encode($payLink));
        $wpUrl = '24card.org';
        if ($site == 'aepro' || $isDev) {
            $wpUrl = 'aepay.tv';
        }
        if ($payment === 'paycec') {
            $wpUrl = '24gift.org';
        }
        $payLink = 'https://' . $wpUrl . '/checkout?' . http_build_query($payLink);
        
        return $this->sendResponse('Success', ['url' => $payLink]);
    }
}
