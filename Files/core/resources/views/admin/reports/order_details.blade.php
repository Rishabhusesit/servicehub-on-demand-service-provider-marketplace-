@extends('admin.layouts.app')
@section('panel')
    <div class="content-wrapper">
        <div class="container-fluid p-0">
            <div class="card">
                <div class="card-body p-xl-4">
                    <div class="invoice" id="invoice">
                        <div class="d-flex align-items-center justify-content-between gap-4 flex-wrap">
                            <div class="left">
                                @php echo $order->orderStatusBadge; @endphp
                                <h6 class="mt-2">
                                    <i class="fa fa-location-arrow">
                                    </i> @lang('Order ID:') {{ $order->order_id }}
                                </h6>
                            </div>
                            <div class="right">
                                <p class="mb-1">
                                    <span class="fw-semibold me-1">@lang('Order ID:')</span>
                                    {{ $order->order_id }}
                                </p>
                                <p>
                                    <span class="fw-semibold me-1">@lang('Order Date:')</span>
                                    {{ showDateTime($order->created_at, 'd M Y') }}
                                    @if ($order->provider)
                                </p>
                                <p class="mb-1">
                                    <span class="fw-semibold me-1">@lang('Provider Name:')</span>
                                    {{ __($order?->provider?->fullname) }}
                                </p>
                                <p>
                                    <span class="fw-semibold me-1">@lang('Address:')</span>
                                    {{ __($order?->provider?->address) }}
                                </p>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row invoice-info mb-4">
                            <div class="col-md-6">
                                <h5 class="mb-3 fw-semibold">@lang('User Details')</h5>
                                <ul>
                                    <li>
                                        <span class="fw-semibold me-1">
                                            @lang('Name'):
                                        </span>
                                        {{ __($order?->user?->fullname) }}
                                    </li>
                                    <li>
                                        <span class="fw-semibold me-1">
                                            @lang('Address'):
                                        </span>
                                        {{ __($order?->user?->address) }}
                                    </li>
                                    <li>
                                        <span class="fw-semibold me-1">
                                            @lang('State'):
                                        </span>
                                        {{ __($order?->user?->state) }}
                                    </li>
                                    <li>
                                        <span class="fw-semibold me-1">
                                            @lang('City'):
                                        </span>
                                        {{ __($order?->user?->city) }}
                                    </li>
                                    <li>
                                        <span class="fw-semibold me-1">
                                            @lang('Zip'):
                                        </span>
                                        {{ __($order?->user?->zip) }}
                                    </li>
                                    <li>
                                        <span class="fw-semibold me-1">
                                            @lang('Country'):
                                        </span>
                                        {{ __($order?->user?->country_name) }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="w-max-content">
                                    <h5 class="mb-3 fw-semibold">@lang('Shipping Address')</h5>
                                    <ul>
                                        <li>
                                            <span class="fw-semibold me-1">
                                                @lang('Name'):
                                            </span>
                                            {{ __($order->contact_person_name) }}
                                        </li>
                                        <li>@lang('Number'):
                                            {{ __($order->contact_person_number) }}
                                        </li>
                                        <li>
                                            <span class="fw-semibold me-1">
                                                @lang('City'):
                                            </span>
                                            {{ __($order?->city?->name) }}
                                        </li>
                                        <li>
                                            <span class="fw-semibold me-1">
                                                @lang('Area'):
                                            </span>
                                            {{ __($order?->area?->name) }}
                                        </li>
                                        <li>
                                            <span class="fw-semibold me-1">
                                                @lang('Address'):
                                            </span>
                                            {{ __($order->address) }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-bordered">
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
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
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
                            </div>
                            <div class="col-lg-6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="50%">@lang('Subtotal')</th>
                                                <td width="50%">{{ showAmount($order->sub_total) }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Delivery Charge') (<i class="la la-plus"></i>) </th>
                                                <td>{{ showAmount($order->delivery_charge) }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('Discount') (<i class="la la-minus"></i>) </th>
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
                            <div class="col-12">
                                <a href="{{ route('admin.report.order.invoice.print', $order->id) }}" target="_blank"
                                    class="btn btn--primary mt-4 w-100">
                                    <i class="las la-print"></i>@lang('Print')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .w-max-content {
            width: max-content;
            margin-left: auto;
        }

        .invoice-info ul li {
            margin-bottom: 6px;
        }
    </style>
@endpush
