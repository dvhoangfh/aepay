<!doctype html>
<html style="overflow: hidden; height: 100%">
<head>
    <meta charset="UTF-8">
    {{--    <meta name="base-url" content="{{ config('app.url') }}">--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/aepay/aesport/css/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('vendor/aepay/aesport/css/bootstrap/bootstrap-utilities.min.css') }}">
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="{{ asset('vendor/aepay/aesport/css/app.css?v=1') }}" rel="stylesheet">
    <style>
        @if($site === 'aehub')
            :root {
            --dark: rgb(30 31 33);
        }
        @endif
    </style>
</head>
<body class="page">
<div id="iframe-package-page"></div>
@paddleJS
<script>
    @if(config('cashier.sandbox'))
    Paddle.Environment.set('sandbox');
    @endif
    var Packages = '@json($packages)';
    var UserId = '@json($user_id)';
    var User = '@json($user)';
    var planId = '{{ request()->query('plan_id') }}';
    var Plans = '@json($plans)';
    var PaymentId = '@json($paymentId)';
    var Vouchers = '@json($vouchers)';
    var Site = '@json($site)';
    var UrlRedirect = '@json($url_redirect)';
    var IsEnablePaypal = '@json($is_enable_paypal)';
    var IsEnableBytePay = '@json($is_enable_bytepay)';
    var IsEnableSellix = '@json($is_enable_sellix)';

    function sendEventToParent(event, data) {
        parent.postMessage(JSON.stringify({
            event,
            data
        }), "*");
    }
</script>
<script
        src="https://www.paypal.com/sdk/js?client-id={{ $client_id }}&vault=true&intent=subscription"
        data-sdk-integration-source="button-factory"></script>
@if(app()->environment() !== 'local')
    <script src="{{ mix('/js/app.js', 'vendor/aepay') }}"></script>
@else
    <script src="{{ asset('vendor/aepay/js/app_local.js') }}"></script>
@endif
</body>
</html>
