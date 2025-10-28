@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="profile-wrapper--lg">
        <div class="notice"></div>
        <div class="walet-wrapper pb-60">
            <div class="row gy-4">
                <div class="col-xsm-6 col-sm-6 col-xl-4">
                    <a href="{{ route('user.order.history') }}" class="walet-card">
                        <div class="walet-card__content">
                            <p class="walet-card__title">@lang('Total Order')</p>
                            <h4 class="walet-card__amount">
                                {{ $totalOrder }}</h4>
                        </div>
                        <div class="walet-card__icon">
                            <img src="{{ asset($activeTemplateTrue . 'images/order.png') }}" alt="image">
                        </div>
                    </a>
                </div>
                <div class="col-xsm-6 col-sm-6 col-xl-4">
                    <a href="{{ route('user.order.history') }}" class="walet-card">

                        <div class="walet-card__content">
                            <p class="walet-card__title">@lang('Complete Order')</p>
                            <h4 class="walet-card__amount">
                                {{ $completeOrder }}</h4>
                        </div>
                        <div class="walet-card__icon">
                            <img src="{{ asset($activeTemplateTrue . 'images/order_total.png') }}" alt="image">
                        </div>
                    </a>
                </div>
                <div class="col-12 col-xl-4">
                    <a href="{{ route('user.order.history') }}" class="walet-card">

                        <div class="walet-card__content">
                            <p class="walet-card__title">@lang('Cancel Order')</p>
                            <h4 class="walet-card__amount">
                                {{ $cancelOrder }}</h4>
                        </div>
                        <div class="walet-card__icon">
                            <img src="{{ asset($activeTemplateTrue . 'images/order_cancel.png') }}" alt="image">
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="transaction-history">
            <div class="profile-header mb-4 gap-3 flex-between">
                <h5 class="profile-header__title">@lang('Latest Order History')</h5>

            </div>
            <table class="table table--collapse table--responsive--xl">
                <thead>
                    <tr>
                        <th>@lang('Order ID')</th>
                        <th>@lang('Schedule')</th>
                        <th>@lang('Price')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Details')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->schedule_time }}<br>{{ showDateTime($order->schedule_date) }}</td>
                            <td>{{ showAmount($order->total) }}</td>
                            <td>
                                @php
                                    echo $order->orderStatusBadge;
                                @endphp
                            </td>
                            <td>
                                <a href="{{ route('user.order.details', $order->id) }}" class="details-btn"><span
                                          class="icon"><i class="las la-angle-right"></i></span></a>
                            </td>
                        </tr>

                    @empty
                        <x-empty-message />
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection


@push('style')
    <style>
        .walet-card {
            color: #414141 !important;
        }
    </style>
@endpush
