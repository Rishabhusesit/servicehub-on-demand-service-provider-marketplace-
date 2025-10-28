@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="show-filter mb-3 text-end">
                <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm"><i class="las la-filter"></i>
                    @lang('Filter')</button>
            </div>
            <div class="card responsive-filter-card mb-4">
                <div class="card-body">
                    <form>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="flex-grow-1">
                                <label>@lang('TRX/Username')</label>
                                <input type="search" name="search" value="{{ request()->search }}" class="form-control">
                            </div>
                            <div class="flex-grow-1">
                                <label>@lang('Payment Type')</label>
                                <select name="payment_type" class="form-control select2">
                                    <option value="">@lang('All')</option>
                                    <option value="{{ Status::COD_PAYMENT }}" @selected(request()->payment_type == Status::COD_PAYMENT)>@lang('Cash on Delivery')
                                    </option>
                                    <option value="{{ Status::ONLINE_PAYMENT }}" @selected(request()->payment_type == Status::ONLINE_PAYMENT)>
                                        @lang('Online Payment')</option>
                                </select>
                            </div>

                            <div class="flex-grow-1">
                                <label>@lang('Order Status')</label>
                                <select name="status" class="form-control select2">
                                    <option value="" selected>@lang('All')</option>
                                    <option value="{{ Status::ORDER_PENDING }}" @selected(request()->status == (string) Status::ORDER_PENDING)>
                                        @lang('Pending')</option>
                                    <option value="{{ Status::ORDER_PROCESSING }}" @selected(request()->status == Status::ORDER_PROCESSING)>
                                        @lang('Processing')</option>
                                    <option value="{{ Status::ORDER_COMPLETED_REQUEST }}" @selected(request()->status == Status::ORDER_COMPLETED_REQUEST)>
                                        @lang('Request for completed')</option>
                                    <option value="{{ Status::ORDER_COMPLETED }}" @selected(request()->status == Status::ORDER_COMPLETED)>
                                        @lang('Completed')</option>
                                    <option value="{{ Status::ORDER_CANCEL }}" @selected(request()->status == Status::ORDER_CANCEL)>
                                        @lang('Cancelled')</option>
                                </select>
                            </div>

                            <div class="flex-grow-1">
                                <label>@lang('Date')</label>
                                <input name="date" type="search"
                                    class="datepicker-here form-control bg--white pe-2 date-range"
                                    placeholder="@lang('Start Date - End Date')" autocomplete="off" value="{{ request()->date }}">
                            </div>

                            <div class="flex-grow-1 align-self-end">
                                <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i>
                                    @lang('Filter')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Order ID')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Provider')</th>
                                    <th>@lang('Schedule')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Payment Type')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Details')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->order_id }}</td>
                                        <td>
                                            <span class="fw-bold">{{ $order?->user?->fullname }}</span>
                                            <br>
                                            <span class="small"> <a
                                                    href="{{ appendQuery('search', $order?->user?->username) }}"><span></span>{{ $order?->user?->username }}</a>
                                            </span>
                                        </td>
                                        <td>
                                            @if ($order->provider)
                                                <span class="fw-bold">{{ $order?->provider?->fullname }}</span>
                                                <br>
                                                <span class="small"> <a
                                                        href="{{ appendQuery('search', $order?->provider?->username) }}"><span></span>{{ $order?->provider?->username }}</a>
                                                </span>
                                            @else
                                                <span class="fw-bold text-danger">@lang('Not Accepted')</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->schedule_time }}<br>{{ showDateTime($order->schedule_date) }}</td>
                                        <td>{{ showAmount($order->total) }}</td>

                                        <td>
                                            @php
                                                echo $order->paymentTypeBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            @php
                                                echo $order->orderStatusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.report.order.details', $order->id) }}"
                                                class="btn btn-sm btn-outline--primary ms-1"><i class="las la-eye"></i>
                                                @lang('Details')</a>
                                        </td>
                                    </tr>
                                @empty
                                   <x-empty-message />
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($orders->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($orders) }}
                    </div>
                @endif
            </div><!-- card end -->
        </div>
    </div>
@endsection


@push('script-lib')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        (function($) {
            "use strict"

            const datePicker = $('.date-range').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                showDropdowns: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                        .endOf('month')
                    ],
                    'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                    'This Year': [moment().startOf('year'), moment().endOf('year')],
                },
                maxDate: moment()
            });
            const changeDatePickerText = (event, startDate, endDate) => {
                $(event.target).val(startDate.format('MMMM DD, YYYY') + ' - ' + endDate.format('MMMM DD, YYYY'));
            }


            $('.date-range').on('apply.daterangepicker', (event, picker) => changeDatePickerText(event, picker
                .startDate, picker.endDate));


            if ($('.date-range').val()) {
                let dateRange = $('.date-range').val().split(' - ');
                $('.date-range').data('daterangepicker').setStartDate(new Date(dateRange[0]));
                $('.date-range').data('daterangepicker').setEndDate(new Date(dateRange[1]));
            }

        })(jQuery)
    </script>
@endpush
