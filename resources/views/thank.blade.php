<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>AiSport- Welcome</title>
    <link href="{{ mix('css/theme.min.css', 'build') }}" rel="stylesheet"/>
</head>
<body class="antialiased border-primary d-flex flex-column">
<div class="page page-center">
    <div class="container py-4">
        <div class="empty">
            <div class="empty-header">Thank you for subscription</div>
            <p class="empty-title">{{ config('app.name') }} content is enabled for you</p>
            @if($data)
            <table class="table">
                <tr>
                    <td>Plan</td>
                    <td>{{ $data['checkout']['title'] ?? '' }}</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>{{ $data['order']['formatted_total'] ?? '' }}</td>
                </tr>
                <tr>
                    <td>Receipt</td>
                    <td><a href="{{ $data['order']['receipt_url'] ?? '' }}" target="_blank">View here</a></td>
                </tr>
            </table>
            @endif
            <a href="{{ route('home') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="5" y1="12" x2="19" y2="12"></line><line x1="5" y1="12" x2="11" y2="18"></line><line x1="5" y1="12" x2="11" y2="6"></line></svg>
                Take me home
            </a>
        </div>
    </div>
</div>
</body>
</html>
