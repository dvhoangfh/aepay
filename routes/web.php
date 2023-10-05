<?php

use Dvhoangfh\Aepay\Http\Controllers\IframeController;
use Dvhoangfh\Aepay\Http\Controllers\WebhookPayPalController;
use Dvhoangfh\Aepay\Http\Controllers\WebhookController;
use Dvhoangfh\Aepay\Http\Controllers\WebhookBytePayController;
use Dvhoangfh\Aepay\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::get('/package-iframe', [IframeController::class, 'index'])->name('iframe');

Route::post('/paypal/webhook', WebhookPayPalController::class)->name('paypal.webhook');
Route::post('/paddle/webhook', WebhookController::class)->name('cashier.webhook');
Route::post('/bytepay/webhook', WebhookBytePayController::class)->name('bytepay.webhook');


Route::post('/api/paylink', [PackageController::class, 'getPayLink'])->name('package.paylink');
Route::get('/api/paylink_uid', [PackageController::class, 'getPayLinkUid'])->name('package.paylink_uid');
Route::post('/api/paylink-bytepay', [PackageController::class, 'getPayLinkBytePay'])->name('package.paylink_bytepay');
Route::post('/api/charge', [PackageController::class, 'charge'])->name('charge');
Route::post('/api/handle_log', [PackageController::class, 'handleLog'])->name('handle_log');