<?php

namespace Dvhoangfh\Aepay\Commands;

use Dvhoangfh\Aepay\Models\BytePayOrder;
use Dvhoangfh\Aepay\Models\WordpressOrder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class ReportOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startDate = now()->subDay()->startOfDay()->addHours(12);
        $endDate = now()->startOfDay()->addHours(12);
        $orders = WordpressOrder::with('package')->where('status', '!=', 'CREATED')->whereBetween('start_at', [$startDate, $endDate])
            ->get();
        $totalOrder = $orders->count();
        $totalAmount = $orders->reduce(function (?int $amount, $order) {
            return $amount + $order->amount;
        });
        $orderPackage = [];
        foreach ($orders as $order) {
            if (!isset($orderPackage[$order->package->package_hash_id])) {
                $orderPackage[$order->package->package_hash_id] = ['count' => 0, 'amount' => 0, 'name' => ''];
            }
            $orderPackage[$order->package->package_hash_id]['count'] += 1;
            $orderPackage[$order->package->package_hash_id]['amount'] += $order->amount;
            $orderPackage[$order->package->package_hash_id]['name'] = $order->package->name;
        }
        $telegram = new Api(config('services.telegram-bot-api.token'));
        $text = "Report: $startDate - $endDate\n" .
            "Tổng $totalOrder đơn - $$totalAmount \n";
        foreach ($orderPackage as $op) {
            $text .= $op['name'] . " - " . $op['count'] ." đơn - $". $op['amount'] . "\n";
        }
        $telegram->sendMessage(
            [
                'chat_id'    => config('services.telegram-bot-api.chat_id'),
                'parse_mode' => 'HTML',
                'text'       => $text
            ]
        );
    }
}
