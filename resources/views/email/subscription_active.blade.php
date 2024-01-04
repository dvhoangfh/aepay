<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>
</head>
<body
    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: #718096; height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important;">

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation"
       style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; margin: 0; padding: 0; width: 100%;">
    <tr>
        <td align="center"
            style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative;">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation"
                   style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; margin: 0; padding: 0; width: 100%;">
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0"
                        style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%; background-color: #edf2f7; border-bottom: 1px solid #edf2f7; border-top: 1px solid #edf2f7; margin: 0; padding: 0; width: 100%;">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                               role="presentation"
                               style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px; background-color: #ffffff; border-color: #e8e5ef; border-radius: 2px; border-width: 1px; box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015); margin: 35 auto; padding: 0; width: 570px;">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell"
                                    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; max-width: 100vw; padding: 32px;">
                                    <h1 style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; color: #3d4852; font-size: 18px; font-weight: bold; margin-top: 0; text-align: left;">
                                        Dear Customer,</h1>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                        AESport - Live Streaming service
                                    </p>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                        We're writing to confirm that your subscription has been added
                                        for {{ $data['package'] }},
                                        starting with {{ $data['start_time']->format('d-M-Y') }}.
                                    </p>
                                    <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 10px">
                                        <thead>
                                        <tr>
                                            <th align="right">
                                                Description
                                            </th>
                                            <th align="right">
                                                Total
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="font-size: 14px; vertical-align: middle; margin: 0; padding: 9px 0; "
                                                align="right">
                                                {{ $data['package'] }} membership
                                            </td>
                                            <td style="font-size: 14px; vertical-align: middle; margin: 0; padding: 9px 0;"
                                                align="right">${{ $data['amount'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                align="right">
                                                Sub total
                                            </td>
                                            <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                align="right">${{ $data['amount'] }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                align="right">
                                                Credit
                                            </td>
                                            <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                align="right">$0
                                            </td>
                                        </tr>
                                        <tr class="total">
                                            <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0; font-weight:bold;"
                                                align="right"
                                                width="80%">Total
                                            </td>
                                            <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0; font-weight:bold;"
                                                align="right">${{ $data['amount'] }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div style="margin-top: 10px">
                                        <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 14px; line-height: 1.5em; margin-top: 0; text-align: left;">
                                            Transaction
                                        </p>
                                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 10px">
                                            <tbody>
                                            <tr>
                                                <td style="font-size: 14px; vertical-align: middle; margin: 0; padding: 9px 0; "
                                                    align="right">
                                                    Transaction date
                                                </td>
                                                <td style="font-size: 14px; vertical-align: middle; margin: 0; padding: 9px 0;"
                                                    align="right">{{ $data['start_time']->format('d-M-Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                    align="right">
                                                    Gateway
                                                </td>
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                    align="right">Paypal
                                                </td>
                                            </tr>
                                            <tr class="total">
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                    align="right"
                                                >Transaction ID
                                                </td>
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                    align="right">{{ $data['transaction_id'] }}
                                                </td>
                                            </tr>
                                            <tr class="total">
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                    align="right"
                                                >Subscription ID
                                                </td>
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                    align="right">{{ $data['subscription_id'] }}
                                                </td>
                                            </tr>
                                            <tr class="total">
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                    align="right">Amount
                                                </td>
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0"
                                                    align="right">${{ $data['amount'] }}
                                                </td>
                                            </tr>
                                            <tr class="total">
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                    align="right">Next billing date
                                                </td>
                                                <td style="font-size: 14px; vertical-align: middle; border-top-width: 1px; border-top-color: #f6f6f6; border-top-style: solid; margin: 0; padding: 9px 0;"
                                                    align="right">{{ $data['next_billing_at'] ? $data['next_billing_at']->format('d-M-Y') : '' }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; font-size: 16px; line-height: 1.5em; margin-top: 30px; text-align: left;">
                                        <a href="https://aesport.tv">AeSport.TV</a> - The leading platforms on live
                                        sports services</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
