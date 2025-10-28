@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-top">
        <div class="table-top__wrapper">
            <form method="GET">
                <div class="table-search">
                    <button type="submit" class="table-search__icon"><i class="las la-search"></i></button>
                    <input type="search" class="form--control" name="search" placeholder="@lang('Search...')"
                           value="{{ request()->search }}">
                </div>
            </form>
        </div>
    </div>
    <table class="table table--border table--responsive--xl table--collapse">
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
                    <td>
                        <div class="text-lg-center text-end">
                            {{ $order->schedule_time }}<br>{{ showDateTime($order->schedule_date) }}
                        </div>
                    </td>
                    <td>{{ showAmount($order->total) }}</td>
                    <td>
                        @php
                            echo $order->orderStatusBadge;
                        @endphp
                    </td>
                    <td>
                        <a href="{{ route('provider.service.details', $order->id) }}" class="details-btn"><span
                                  class="icon"><i class="las la-angle-right"></i></span></a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-muted text-center empty-text" colspan="100%">
                        <div class="empty-message">
                            <span class="empty-message-icon">
                                <img src="{{ asset($activeTemplateTrue . 'images/no-data.gif') }}" alt="">
                            </span>
                            <p class="empty-message-text">
                                {{ __($emptyMessage) }}
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>


    @if ($orders->hasPages())
        <div class="card-footer py-4">
            {{ paginateLinks($orders) }}
        </div>
    @endif
@endsection
