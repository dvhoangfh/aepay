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
</head>
<body class="page">
<div id="gg-iframe-page"></div>
<script>
    var Settings = '@json($data)';
    function sendEventToParent(event, data) {
        parent.postMessage(JSON.stringify({
            event,
            data
        }), "*");
    }
</script>
<script
        src="https://www.paypal.com/sdk/js?client-id={{ $data['client_id'] }}&vault=true&intent=subscription"
        data-sdk-integration-source="button-factory"></script>
@if(app()->environment() !== 'local')
    <script src="{{ mix('/js/app.js', 'vendor/aepay') }}"></script>
@else
    <script src="{{ asset('vendor/aepay/js/app_local.js') }}"></script>
@endif
</body>
</html>
