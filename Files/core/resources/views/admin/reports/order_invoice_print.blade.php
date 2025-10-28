<!DOCTYPE html>
<html>

<head>
    <title>{{ $pageTitle }} - {{ $order->order_id }}</title>
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}">
</head>
<body onload="window.print()">

    <!-- Container -->
    <div class="container-fluid invoice-container">
        <div class="container-fluid p-0">
            <div class="card border-0">
                <div class="card-body">
                    <!-- Main content -->
                    <div class="invoice">
                        <!-- title row -->
                        <div class="row">
                            <div class="col-12">
                                <div class="list--row">
                                    <div class="logo-invoice float-left">
                                        <img src="{{ siteLogo('dark') }}"
                                            alt="logo">
                                    </div>
                                    <ul class="m-0  float-right">
                                        <b>@lang('Order ID:')</b> {{ $order->order_id }}<br>
                                        <b>@lang('Order Date:')</b> {{ showDateTime($order->created_at, 'd M Y') }} <br>
                                        <b>@lang('Total Amount:')</b> {{ showAmount($order->total) }}
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="row invoice-info">
                            <div class="col-12">
                                <div class="list--row">
                                    <div class="float-left">
                                        <h5 class="mb-2">@lang('User Details')</h5>
                                        <address>
                                            <ul>
                                                <li>@lang('Name'): <strong>{{ __($order?->user?->fullname) }}</strong></li>
                                                <li>@lang('Address'): {{ __($order?->user?->address) }}</li>
                                                <li>@lang('State'): {{ __($order?->user?->state) }}</li>
                                                <li>@lang('City'): {{ __($order?->user?->city) }}</li>
                                                <li>@lang('Zip'): {{ __($order?->user?->zip) }}</li>
                                                <li>@lang('Country'): {{ __($order?->user?->country_name) }}</li>
                                            </ul>
                                        </address>
                                    </div><!-- /.col -->

                                    <div class="float-right">
                                        <h5 class="mb-2">@lang('Shipping Address')</h5>

                                        <address>
                                            <ul>
                                                <li>@lang('Name'): <strong>{{ __($order->contact_person_name) }}</strong>
                                                </li>
                                                <li>@lang('Number'): {{ __($order->contact_person_number) }}</li>
                                                <li>@lang('City'): {{ __($order?->city?->name) }}</li>
                                                <li>@lang('Area'): {{ __($order?->area?->name) }}</li>
                                                <li>@lang('Address'): {{ __($order->address) }}</li>
                                            </ul>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <!-- Table row -->

                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table print-table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>@lang('SN.')</th>
                                            <th>@lang('Service')</th>
                                            <th>@lang('Quantity')</th>
                                            <th>@lang('Price')</th>
                                            <th>@lang('Total Price')</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($order->orderDetails as $orderDetail)
                                            @php
                                                $serviceOption = $orderDetail->serviceOption;
                                                $service = $serviceOption->service;
                                                $parentService = $serviceOption->parent;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ __($service?->name) ?? '' }} -
                                                    {{ __($parentService?->name) ?? '' }} -
                                                    {{ __($serviceOption?->name) ?? '' }}
                                                </td>
                                                <td>{{ $orderDetail->qty }}</td>
                                                <td class="text-right">{{ showAmount($orderDetail->price) }}</td>
                                                <td class="text-right">
                                                    {{ showAmount($orderDetail->price * $orderDetail->qty) }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div><!-- /.col -->
                        </div><!-- /.row -->

                        <div class="row mt-4">
                            <!-- accepted payments column -->
                            <div class="col-lg-6">
                                <div class="table-responsive">
                                    <table class="table print-payment-table border-0 float-md-start float-end">
                                        <tbody>
                                            <tr>
                                                <th width="50%">@lang('Payment Method')</td>
                                                <td width="50%"> @php echo $order->paymentTypeBadge @endphp</td>
                                            </tr>

                                            <tr>
                                                <th width="50%">@lang('Payment Status')</td>
                                                <td width="50%"> @php echo $order->paymentStatusBadge @endphp</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Total Payment Amount') </td>
                                                <td>{{ showAmount($order->total) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div><!-- /.col -->
                            <div class="col-lg-6 subtotal-container">
                                <div class="table-responsive">
                                    <table class="table print-payment-table border-0">
                                        <tbody>
                                            <tr>
                                                <th width="50%">@lang('Subtotal')</th>
                                                <td width="50%">{{ showAmount($order->sub_total) }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Delivery Charge') (+) </th>
                                                <td>{{ showAmount($order->delivery_charge) }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Discount') (-) </th>
                                                <td>{{ showAmount($order->discount) }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Total')</th>
                                                <td>{{ showAmount($order->total) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <style>
        .float-left {
            float: left;
        }

        table.table.print-payment-table.border-0 {
            max-width: 500px;
            margin-left: auto;
        }

        .float-right {
            float: right;
        }

        .logo-invoice {
            display: flex;
            align-items: center;
            width: 100%;
            max-width: 200px;
            height: 50px;
        }

        .logo-invoice img {
            width: 100%;
            max-width: 200px;
        }

        .print-table thead tr th {
            font-size: 14px;
            border-bottom: none !important;
        }

        .print-table tbody tr td {
            font-size: 14px;
        }

        .print-payment-table.table tbody tr td:last-child {
            border-right: none !important;
        }

        .print-payment-table tbody tr {
            font-size: 14px;
        }

        .print-payment-table.table>:not(caption)>*>* {
            border-bottom: none;
        }

        .table>:not(:first-child) {
            border-top: none !important;
        }

        .list--row {
            flex-direction: row !important;
        }

        .invoice {
            background: #fff;
            padding: 20px;
        }

        address>ul {
            padding: 0.2rem 2rem;
            padding-left: 0px
        }

        ul li {
            padding: 0;
            line-height: 24px;
            list-style: none !important;
        }

        .table>:not(caption)>*>* {
            padding: 0.3rem 0.5rem;
        }

        @page {
            size: auto;
            margin: 0mm;
        }

        hr {
            border-top: 1px solid #ccc;
        }

        table.table.print-payment-table tbody tr th,
        table.table.print-payment-table tbody tr td {
            border-bottom: 1px dashed hsl(var(--border)) !important;
        }

        .subtotal-container {
            page-break-inside: avoid;
        }

        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    </style>

</body>
</html>
