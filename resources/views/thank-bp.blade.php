@extends('layouts.master')
@section('content')
    <section id="pr-about" class="pr-about-section">
        <div class="container">
            <h1 class="text-center">Successful Order!</h1>
            <div class="mb-8">We, the NoteHive team, are delighted to inform you that your order has been successfully processed. </div>
            <div>Order ID: {{ $data['orderId'] ?? '' }}</div>
            <div>Transaction ID: {{ $data['transId'] ?? '' }}</div>
            <div class="mb-4">Our team will carefully process and package your digital product to ensure it is delivered to you in perfect condition.</div>
            <div class="mb-4">If you have any questions or requests regarding your order, please feel free to reach out to us via our online support system or the following phone number: +60 3-5621 8686. We are more than happy to assist you!</div>
            <div class="mb-4">Our team will carefully process and package your digital product to ensure it is delivered to you in perfect condition.</div>
        </div>
    </section>
    <script>
        var url = '{!! $url !!}';
        console.log(url);
        function sendEventToParent(event, data) {
            parent.postMessage(JSON.stringify({
                event,
                data
            }), "*");
        }
        sendEventToParent('bytepay-success', {
            url: url
        })
    </script>
@endsection

