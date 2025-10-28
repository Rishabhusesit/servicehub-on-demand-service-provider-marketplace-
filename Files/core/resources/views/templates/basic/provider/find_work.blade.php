@extends($activeTemplate . 'layouts.frontend')
@section('content')

    <section class="recent-works py-60">
        <div class="container custom-container">
            <div class="row gy-4 flex-column-reverse flex-lg-row ">
                <div class="col-12">
                    <div class="recent-works__wrapper">
                        <div class="recent-works__top flex-between">
                            <h5 class="recent-works__title mb-0">@lang('Recent Works')</h5>
                            <form class="search-inner">
                                <input type="text" class="form--control form-control" name="search"
                                       value="{{ request()->search }}" placeholder="@lang('Search...')">
                                <button type="submit" class="search-icon"><i class="las la-search"></i></button>
                            </form>
                        </div>

                        @if ($orders->count() > 0)
                            <div class="row gy-4">
                                @foreach ($orders as $order)
                                    @php
                                        $serviceName = $order->details?->first()?->serviceOption?->service ?? '';
                                    @endphp

                                    <div class="col-md-4">
                                        <div class="work-item">
                                            <div class="work-item__top">
                                                <div class="flex-between gap-2 mb-2">
                                                    <h6 class="work-item__title mb-0">
                                                        {{ $serviceName?->name }}</h6>
                                                    <h6 class="work-item__price mb-0">
                                                        {{ showAmount($order->total) }}</h6>
                                                </div>
                                                <p class="work-item__subTitle">
                                                    @if ($order->details->isNotEmpty())
                                                        @foreach ($order->details as $detail)
                                                            @php
                                                                $serviceOption = $detail?->serviceOption;
                                                            @endphp
                                                            <span>
                                                                {{ $serviceOption?->name ?? '' }}
                                                                x {{ $detail->qty }}
                                                            </span>
                                                            @if (!$loop->last)
                                                                <span>|</span>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        <span>@lang('No details')</span>
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="work-item__body">
                                                <ul class="work-itemlist">
                                                    <li class="work-itemlist__item"> <span
                                                              class="bold">@lang('Client Name:')</span>
                                                        {{ __($order?->user?->fullname) }}
                                                    </li>
                                                    <li class="work-itemlist__item"> <span
                                                              class="bold">@lang('Mobile Number:')</span>
                                                        {{ __($order?->user?->dial_code) }}{{ __($order?->user?->mobile) }}</li>
                                                    <li>
                                                        <ul class="work-itemlist__shedule">
                                                            <li class="work-itemlist__shedule-item"> <span
                                                                      class="text">@lang('Schedule'):</span>
                                                                {{ $order->schedule_time }}
                                                            </li>
                                                            <li class="work-itemlist__shedule-item"> <span
                                                                      class="text">@lang('Date:')</span>
                                                                {{ showDateTime($order->schedule_date) }}
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="work-itemlist__item"> <span
                                                              class="bold">@lang('Address:')</span>
                                                        {{ __($order?->user?->address) }},
                                                        {{ __($order?->city?->name) }},{{ __($order?->area?->name) }}
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="work-item__button">
                                                <button type="button" class="btn btn-outline--base w-100 confirmationBtn"
                                                        data-action="{{ route('provider.order.accept', $order->id) }}"
                                                        data-question="@lang('Are you sure to accept this order?')">
                                                    @lang('Accept Request')</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @if ($orders->hasPages())
                                <div class="card-footer py-4">
                                    {{ paginateLinks($orders) }}
                                </div>
                            @endif
                        @else
                            <x-empty-message />
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-confirmation-modal closeBtn="btn--dark btn--sm" submitBtn="btn--base btn--sm" />
@endsection

@push('style')
    <style>
        .no-found-image {
            max-height: 200px;
        }
    </style>
@endpush
