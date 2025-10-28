@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="order-confirmation my-60">
        <div class="container custom-container">
            <div class="order-details__tab">
                <div class="nav custom--tab nav-tabs" id="nav-tab" role="tablist">
                    <div class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#Details" type="button">@lang('Details')</button>
                    </div>
                    @if ($order->provider)
                        <div class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#ServiceProvider" type="button">@lang('Service Provider')</button>
                        </div>
                    @endif
                </div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane active" id="Details">
                        <div class="row gy-5">
                            <div class="col-xl-8">
                                @if (count($order->trackOrder) > 0)
                                    <div class="order-card">
                                        <div class="order-timeline">
                                            <h5 class="order-card__heading">@lang('TimeLine') - {{ $order->order_id }}
                                            </h5>
                                            <ul class="order-timeline__list">
                                                @foreach ($order->trackOrder as $track)
                                                    <li class="order-timeline-item">
                                                        <div class="left">
                                                            <p class="timeline-date">
                                                                {{ showDateTime($track->created_at, 'd M Y') }}</p>
                                                            <div class="divide"></div>
                                                            <p class="text">{{ __($track->message) }}</p>
                                                        </div>
                                                        <p class="timeline-time">
                                                            {{ showDateTime($track->created_at, 'h:i A') }}</p>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                <div class="row gy-4">
                                    <div class="col-xl-5">
                                        <div class="product-card">
                                            <div class="product-card__image">
                                                <img src="{{ getImage(getFilePath('service') . '/' . $serviceName?->image, getFileSize('service')) }}" alt="@lang('image')">
                                            </div>

                                            <div class="product-card__content">
                                                <p class="product-card__text">{{ $order->order_id }} -
                                                    @php echo $order->orderStatusBadge; @endphp
                                                </p>
                                                <h5 class="product-card__heading">{{ __($serviceName?->name) }}</h5>
                                                <p class="product-card__price mb-0">{{ showAmount($order->total) }}</p>

                                                <div class="row">
                                                    @if ($order->payment_status == Status::UNPAID && $order->status != Status::ORDER_CANCEL && $order->status != Status::ORDER_COMPLETED && $order->status != Status::ORDER_PROCESSING && $order->status != Status::ORDER_COMPLETED_REQUEST)
                                                        <div class="col-md-6">
                                                            <button type="button" class="w-100 btn--md btn-outline--base text-center confirmationBtn mt-3 mt-md-3" data-action="{{ route('user.order.cancel', $order->id) }}" data-question="@lang('Are you sure to cancel this order?')">
                                                                @lang('Cancel')</button>

                                                        </div>
                                                    @endif

                                                    <div class="col-md-6">
                                                        @if (!in_array($order->status, [Status::ORDER_COMPLETED, Status::ORDER_REFUND_APPROVED]))
                                                            <button type="button" class="w-100 btn--md btn-outline--base text-center confirmationBtn mt-3 mt-md-3" data-action="{{ route('user.order.refund', $order->id) }}" data-question="@lang('Are you sure to refund this order?')">
                                                                @lang('Refund')</button>
                                                        @endif
                                                    </div>
                                                </div>


                                                @if ($order->status == Status::ORDER_COMPLETED && $order->review_status != Status::YES)
                                                    <a href="{{ route('user.order.review', $order->id) }}" class="w-100 btn--md btn-outline--base text-center">@lang('Write an review')</a>
                                                @endif

                                                @if ($order->status != Status::ORDER_COMPLETED && $order->status != Status::ORDER_CANCEL && $order->status == Status::ORDER_COMPLETED_REQUEST)
                                                    <div class="summery-block mt-4 text-center">
                                                        <button type="button" class="w-100 btn--md btn--base btn--sm text-white text-center confirmationBtn" data-action="{{ route('user.order.requested.approve', $order->id) }}" data-question="@lang('Are you sure to complete this order?')">
                                                            @lang('Approve Requested Order')</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>



                                        <div class="comment mt-4">
                                            <h5 class="comment__title">@lang('My Review & Comment')</h5>
                                            @if ($order?->review?->rating)
                                                <div class="flex-align gap-2">
                                                    <ul class="rating-list">
                                                        @php
                                                            $rating = $order?->review?->rating;
                                                        @endphp
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <li class="rating-list__item">
                                                                <span class="rating-list__text">
                                                                    <i class="{{ $i <= $rating ? 'fa fa-star' : 'fa-regular fa-star' }}"></i>
                                                                </span>
                                                            </li>
                                                        @endfor
                                                    </ul>
                                                    <p class="rating-count">
                                                        <span>{{ __($rating) }}</span> / <span>@lang('5')</span>
                                                    </p>
                                                </div>
                                                <div class="comment-wrapper">
                                                    <p class="comment-wrapper__text">
                                                        “{{ optional($order->review)->review }}“</p>
                                                </div>
                                            @else
                                                <x-empty-message />
                                            @endif
                                        </div>


                                    </div>
                                    <div class="col-xl-7">
                                        <div class="order-card">
                                            <div class="order-card__content">
                                                <h5 class="order-card__heading">@lang('Schedule')</h5>
                                                <div class="shedule-wrapper">
                                                    <div class="shedule-item">
                                                        <h5 class="order-card__title">@lang('Time')</h5>
                                                        <p class="text">{{ Str::upper($order->schedule_time) }}</p>
                                                    </div>
                                                    <div class="shedule-divide"></div>
                                                    <div class="shedule-item">
                                                        <h5 class="order-card__title">@lang('Date')</h5>
                                                        <p class="text">{{ showDateTime($order->schedule_date) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="order-card">
                                            <div class="order-card__content">
                                                <h5 class="order-card__heading">@lang('Ordered Information')</h5>
                                                <div class="shedule-info">
                                                    <div class="shedule-info-item">
                                                        <div class="icon">
                                                            <i class="las la-user"></i>
                                                        </div>
                                                        <div class="content">
                                                            <p class="heading">{{ __($user->fullname) }}</p>
                                                            <p class="text">{{ $user->mobile }}</p>
                                                        </div>
                                                    </div>

                                                    <div class="shedule-info-item">
                                                        <div class="icon">
                                                            <i class="las la-home"></i>
                                                        </div>
                                                        <div class="content">
                                                            <p class="heading">@lang('Address')</p>
                                                            <p class="heading">{{ __($order?->user?->address) }}</p>
                                                            <p class="text">
                                                                {{ __($order?->city?->name) }},{{ __($order?->area?->name) }}
                                                            </p>

                                                        </div>
                                                    </div>


                                                    <div class="shedule-info-item">
                                                        <div class="icon">
                                                            <i class="las la-phone"></i>
                                                        </div>
                                                        <div class="content">
                                                            <p class="heading">@lang('Contact Details')</p>
                                                            <p class="text">
                                                                {{ __($order->contact_person_name) }},
                                                                {{ __($order->contact_person_name) }},
                                                                {{ __($order->address) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="summery-box">
                                    <h5 class="summery-box__title">@lang('Bill & Payment')</h5>
                                    <div class="accordion">
                                        <div class=" summery-block__accordion">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#summery">
                                                @lang('Total') <span class="total-price">{{ gs('cur_sym') }}{{ showAmount($order->total, currencyFormat: false) }}</span>
                                            </button>
                                            <div id="summery" class="accordion-collapse collapse show">
                                                <div class="summery-block">



                                                    <ul class="summery-list">
                                                        @foreach ($orderDetails as $orderDetail)
                                                            @php
                                                                $serviceOption = $orderDetail?->serviceOption;
                                                                $service = $serviceOption?->service;
                                                                $parentService = $serviceOption?->parent;
                                                            @endphp
                                                            <li class="summery-list-item">
                                                                <div>
                                                                    <strong class="summery-list-item__title mb-0"> {{ $service?->name ?? '' }}</strong>
                                                                    <div class="summery-list-item__content">

                                                                        @if ($parentService?->name)
                                                                            <span>{{ $parentService?->name ?? '' }}</span>
                                                                        @endif
                                                                        <span>{{ $serviceOption?->name ?? '' }} x {{ $orderDetail->qty }}</span>
                                                                    </div>
                                                                </div>
                                                                <span class="summery-list-item__price">{{ gs('cur_sym') }}{{ showAmount($orderDetail->price * $orderDetail->qty, currencyFormat: false) }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>


                                                    <ul class="list">
                                                        <li class="item">
                                                            <p>@lang('Subtotal')</p>
                                                            <span>{{ gs('cur_sym') }}{{ showAmount($order->sub_total, currencyFormat: false) }}</span>
                                                        </li>
                                                        <li class="item">
                                                            <p>@lang('Delivery Charge')</p>
                                                            <span>{{ gs('cur_sym') }}{{ showAmount($order->delivery_charge, currencyFormat: false) }}</span>
                                                        </li>
                                                        <li class="item">
                                                            <p>@lang('Discount')</p>
                                                            <span>{{ gs('cur_sym') }}{{ showAmount($order->discount, currencyFormat: false) }}</span>
                                                        </li>
                                                    </ul>
                                                    <div class="summery-block__total">
                                                        <strong>@lang('Amount to be paid')</strong>
                                                        <strong>{{ gs('cur_sym') }}{{ showAmount($order->total, currencyFormat: false) }}</strong>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="summery-box__title">@lang('Payment History')</h5>

                                    @if ($order->payment_type == Status::COD_PAYMENT)
                                        <div class="custom--radio">
                                            <i class="las la-wallet"></i>
                                            <label for="cash"> @lang('Cash On Delivery')</label>
                                        </div>
                                    @else
                                        @if ($order->status != Status::ORDER_CANCEL)
                                            <div class="summery-block">
                                                @if ($order->payment_status == Status::PAID)
                                                    <div class="summery-block">
                                                        <div class="payment-item">
                                                            <div class="payment-item__right">
                                                                <div class="payment-image">
                                                                    <img src="{{ getImage(getFilePath('gateway') . '/' . $gateWayInfo?->image) }}" alt="">
                                                                </div>
                                                                <p>{{ $gateWayInfo?->name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="payment-user-info">
                                                            <div class="text"> <span class="bold">@lang('Transaction ID'):</span> {{ $orderPayment?->trx }}</div>

                                                        </div>
                                                    </div>

                                                    <div class="summery-block">
                                                        <div class="payment-success flex-align">
                                                            <div class="payment-success__image">
                                                                <img src="{{ asset($activeTemplateTrue . '/images/success.png') }}" alt="@lang('image')">
                                                            </div>
                                                            <h5 class="payment-success__heading">@lang('Payment completed successfully')</h5>
                                                        </div>
                                                    </div>
                                                @else
                                                    @if ($orderPayment && $orderPayment->status == Status::PAYMENT_PENDING)
                                                        <div class="summery-block">
                                                            <div class="payment-item">
                                                                <div class="payment-item__right">
                                                                    <div class="payment-image">
                                                                        <img src="{{ getImage(getFilePath('gateway') . '/' . $gateWayInfo?->image) }}" alt="">
                                                                    </div>
                                                                    <p>{{ $gateWayInfo?->name }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="payment-user-info">
                                                                <div class="text"> <span class="bold">@lang('Transaction ID'):</span>{{ $orderPayment?->trx }}</div>
                                                            </div>
                                                        </div>

                                                        <div class="summery-block">
                                                            <div class="payment-success flex-align">
                                                                <div class="payment-success__image">
                                                                    <img src="{{ asset($activeTemplateTrue . '/images/pending.png') }}" alt="@lang('image')">
                                                                </div>
                                                                <h5 class="payment-success__heading">@lang('You have payment request has been taken')</h5>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="summery-block">
                                                            <form action="{{ route('user.deposit.insert', $order->id) }}" method="post" class="deposit-form">
                                                                @csrf
                                                                <input type="hidden" name="currency">
                                                                <div class="gateway-card p-3">
                                                                    <div class="row justify-content-center gy-4">
                                                                        <div class="col-lg-12">
                                                                            <div class="payment-system-list is-scrollable gateway-option-list">
                                                                                @foreach ($gatewayCurrency as $data)
                                                                                    <label for="{{ titleToKey($data->name) }}" class="payment-item @if ($loop->index > 4) d-none @endif gateway-option">
                                                                                        <span class="payment-check d-none">
                                                                                            <input class="payment-item__radio gateway-input" id="{{ titleToKey($data->name) }}" type="radio" name="gateway" value="{{ $data->method_code }}" data-gateway='{{ json_encode($data) }}' data-min-amount="{{ showAmount($data->min_amount) }}" data-max-amount="{{ showAmount($data->max_amount) }}" @checked(old('gateway', $loop->first) == $data->method_code)>
                                                                                        </span>
                                                                                        <span class="payment-item__right">
                                                                                            <span class="payment-item__info">
                                                                                                <span class="payment-item__check"></span>
                                                                                                <span class="payment-item__name">{{ __($data->name) }}</span>
                                                                                            </span>
                                                                                            <span class="payment-item__thumb">
                                                                                                <img class="payment-item__thumb-img" src="{{ getImage(getFilePath('gateway') . '/' . $data?->method?->image) }}" alt="@lang('payment-thumb')">
                                                                                            </span>
                                                                                        </span>
                                                                                    </label>
                                                                                @endforeach
                                                                                @if ($gatewayCurrency->count() > 4)
                                                                                    <button type="button" class="payment-item__btn more-gateway-option">
                                                                                        See More
                                                                                        <span class="icon">
                                                                                            <i class="la la-arrow-down"></i>
                                                                                        </span>
                                                                                    </button>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <!-- Payment Information -->
                                                                        <div class="col-lg-12">
                                                                            <div class="payment-system-list">
                                                                                <div class="deposit-info">
                                                                                    <div class="deposit-info__title">
                                                                                        <p class="text has-icon">
                                                                                            @lang('Limit')
                                                                                            <span></span>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="deposit-info__input">
                                                                                        <p class="text"><span class="gateway-limit">@lang('0.00')</span>
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="deposit-info">
                                                                                    <div class="deposit-info__title">
                                                                                        <p class="text has-icon">
                                                                                            @lang('Processing Charge')
                                                                                            <span data-bs-toggle="tooltip" title="@lang('Processing charge for payment gateways')" class="proccessing-fee-info"><i class="las la-info-circle"></i>
                                                                                            </span>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="deposit-info__input">
                                                                                        <p class="text"><span class="processing-fee">@lang('0.00')</span>
                                                                                            {{ __(gs('cur_text')) }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="deposit-info total-amount pt-3">
                                                                                    <div class="deposit-info__title">
                                                                                        <p class="text">@lang('Total')
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="deposit-info__input">
                                                                                        <p class="text"><span class="final-amount">@lang('0.00')</span>
                                                                                            {{ __(gs('cur_text')) }}</p>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="deposit-info gateway-conversion d-none total-amount pt-2">
                                                                                    <div class="deposit-info__title">
                                                                                        <p class="text">@lang('Conversion')
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="deposit-info__input">
                                                                                        <p class="text"></p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="deposit-info conversion-currency d-none total-amount pt-2">
                                                                                    <div class="deposit-info__title">
                                                                                        <p class="text">
                                                                                            @lang('In') <span class="gateway-currency"></span>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="deposit-info__input">
                                                                                        <p class="text">
                                                                                            <span class="in-currency"></span>
                                                                                        </p>

                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-none crypto-message mt-4 text-center">
                                                                                    @lang('Conversion with') <span class="gateway-currency"></span>
                                                                                    @lang('and final value will Show on next step')
                                                                                </div>
                                                                                <!-- Submit Button -->
                                                                                <div class="summery-block mt-4">
                                                                                    <button type="submit" class="btn btn--md btn--base w-100" disabled>
                                                                                        @lang('Make Payment')
                                                                                    </button>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    @endif

                                                @endif
                                            </div>
                                        @endif

                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($order->provider)
                        <div class="tab-pane" id="ServiceProvider">
                            <div class="row gy-5">
                                <div class="col-xl-4">
                                    <div class="provider-profile">
                                        <div class="provider-profile__wrapper">
                                            <div class="provider-profile__image">
                                                <img src="{{ getImage(getFilePath('providerProfile') . '/' . $order?->provider?->image, getFileSize('providerProfile'), true) }}" alt="@lang('image')">
                                            </div>
                                            <div class="content">
                                                <h5 class="provider-profile__designation">
                                                    {{ $order?->provider?->fullname }}</h5>
                                                <p class="provider-profile__name">{{ $order?->provider?->address }}</p>
                                                <p class="flex-align provider-profile__tel"><span class="icon"><i class="las la-phone"></i></span>
                                                    ({{ $order?->provider?->dial_code }}) {{ $order?->provider?->mobile }}
                                                </p>

                                                <div class="profile-card__rating">
                                                    <ul class="rating-list mb-1">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <li class="rating-list__item">
                                                                <span class="rating-list__text">
                                                                    @if ($i <= floor($order?->provider?->average_rating))
                                                                        <i class="fa fa-star"></i>
                                                                    @elseif ($i == ceil($order?->provider?->average_rating) && $order?->provider?->average_rating - floor($order?->provider?->average_rating) >= 0.5)
                                                                        <i class="fa fa-star-half-alt"></i>
                                                                    @else
                                                                        <i class="fa-regular fa-star"></i>
                                                                    @endif
                                                                </span>
                                                            </li>
                                                        @endfor
                                                        &nbsp;&nbsp;
                                                        <span>{{ __(round($order?->provider?->average_rating, 1)) }}</span>
                                                        /
                                                        <span>@lang('5')</span>
                                                    </ul>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-8">

                                    <div class="chat custom--card">
                                        <div class="chat__heading">
                                            <div class="chat__heading-icon"><i class="lar la-comment"></i></div>
                                            <h5 class="chat__heading-title">{{ __($order?->provider?->fullname) }}</h5>
                                        </div>
                                        <div class="chat__body" id="chatBody">
                                            @foreach ($conversation as $message)
                                                @if ($message->is_user == Status::YES)
                                                    <div class="message-item form--message">
                                                        <div class="message-item__wrapper">
                                                            <p class="message-item__text">{{ __($message->message) }}
                                                                <br>
                                                                @if ($message->attachment != null)
                                                                    <a href="{{ getImage(getFilePath('conversation') . '/' . $message->attachment) }}" download="{{ $message->attachment }}">@lang('Download File')</a>
                                                                @endif
                                                            </p>
                                                            <div class="message-item__profile">
                                                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $order?->user?->image, getFileSize('userProfile'), true) }}" alt="profile-image">
                                                            </div>
                                                        </div>

                                                    </div>
                                                @else
                                                    <div class="message-item">
                                                        <div class="message-item__wrapper">
                                                            <div class="message-item__profile">
                                                                <img src="{{ getImage(getFilePath('providerProfile') . '/' . $order?->provider?->image, getFileSize('providerProfile'), true) }}" alt="profile-image">
                                                            </div>
                                                            <p class="message-item__text">{{ __($message->message) }}
                                                                <br>
                                                                @if ($message->attachment != null)
                                                                    <a href="{{ getImage(getFilePath('conversation') . '/' . $message->attachment) }}" download="{{ $message->attachment }}">@lang('Download File')</a>
                                                                @endif

                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="chat__footer">
                                            <form action="{{ route('user.message.send', $order->id) }}" class="w-100 messageForm chat__footer-wrapper" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="chat-file">
                                                    <input type="file" class="chat-file__input" name="attachment">
                                                    <label class="chat-file__icon"><i class="las la-paperclip"></i></label>
                                                </div>

                                                <div class="chat__box">
                                                    <input data-emojiable="true" type="text" class="form--control message" name="message" placeholder="@lang('Type message here')" required>
                                                    <button type="submit" class="chat__box-icon"><i class="las la-paper-plane"></i></button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <x-confirmation-modal closeBtn="btn--dark btn--sm" submitBtn="btn--base btn--sm" />
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/emoji.css') }}">
@endpush


@push('style')
    <style>
        .no-found-image {
            max-height: 100px;
        }

        .summery-list {
            margin-bottom: 24px;
        }

        .summery-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .summery-list-item:not(:last-child) {
            margin-bottom: 16px
        }

        .summery-list-item__title {
            color: hsl(var(--black));
            font-weight: 500;
            font-size: 16px;
        }

        .summery-list-item__content span {
            font-size: 16px;
        }

        .summery-list-item__price {
            color: hsl(var(--black));
            font-weight: 500;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/pusher.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/emoji.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/emoji-picker.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            window.addEventListener('load', function() {
                const activeTab = localStorage.getItem('activeTab');
                if (activeTab) {
                    const targetTabButton = document.querySelector(`[data-bs-target='${activeTab}']`);
                    if (targetTabButton) {
                        new bootstrap.Tab(targetTabButton).show();
                    }
                }
                scrollToBottom();
            });

            const tabButtons = document.querySelectorAll('.nav-link');
            tabButtons.forEach(function(tabButton) {
                tabButton.addEventListener('click', function() {
                    localStorage.setItem('activeTab', tabButton.getAttribute('data-bs-target'));
                    scrollToBottom();

                });
            });

            $(function() {
                window.emojiPicker = new EmojiPicker({
                    emojiable_selector: '[data-emojiable=true]',
                    assetsPath: '{{ asset($activeTemplateTrue) }}/images/emoji',
                    popupButtonClasses: 'las la-smile'
                });
                window.emojiPicker.discover();
            });


            const PUSHER_APP_KEY = "{{ gs('pusher_app_key') }}";
            const PUSHER_CLUSTER = "{{ gs('pusher_app_cluster') }}";
            const BASE_URL = "{{ route('home') }}";

            var pusher = new Pusher(PUSHER_APP_KEY, {
                cluster: PUSHER_CLUSTER,
            });


            const pusherConnection = (eventName, channelName) => {
                pusher.connection.bind('connected', () => {
                    const SOCKET_ID = pusher.connection.socket_id;
                    pusher.config.authEndpoint = `${BASE_URL}/pusher/auth/${SOCKET_ID}/${channelName}`;
                    let channel = pusher.subscribe(channelName);
                    channel.bind('pusher:subscription_succeeded', function() {
                        channel.bind(eventName, function(data) {

                            $('.chat__body').append(
                                `    <div class="message-item">
                                                        <div class="message-item__wrapper">
                                                            <div class="message-item__profile">
                                                                <img src="{{ getImage(getFilePath('providerProfile') . '/' . $order?->provider?->image, getFileSize('providerProfile')) }}"
                                                                    alt="profile-image">

                                                            </div>
                                                             <p class="message-item__text">${data.conversation.message}
                                                                <br>
                                                       ${data.conversation.attachment_url ? `<a href="${data.conversation.attachment_url}" download class="attachment-link"> Download File</a>` : ''}
                                                                
                                                            </p>
                                                        </div>
                                                    </div>`
                            )
                            scrollToBottom();
                        })
                    });
                });
            };

            pusherConnection(`user-message`, "private-conversation-{{ $order->id }}");


            $(document).ready(function() {
                $('.messageForm').on('submit', function(e) {
                    e.preventDefault();

                    var formData = $(this).serialize();
                    var message = $('.message').val();

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: new FormData(this),
                        dataType: 'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {

                            if (response.success) {
                                $('.chat__body').append(
                                    `<div class="message-item form--message">
                                    <div class="message-item__wrapper">
                                        <p class="message-item__text">${message}
                                            <br>
                                             ${response.data.attachment_url ? `<a href="${response.data.attachment_url}" download class="attachment-link"> Download File</a>` : ''}
                                            
                                            </p>
                                        <div class="message-item__profile">
                                            <img src="{{ getImage(getFilePath('userProfile') . '/' . $order?->user?->image, getFileSize('userProfile')) }}"
                                                alt="profile-image">
                                        </div>
                                    </div>
                                </div>`
                                )

                                $('.message').text('');
                                scrollToBottom();

                            } else {
                                notify('error', response.message);
                            }
                        }
                    });
                });
            });

            function scrollToBottom() {
                $('#chatBody').scrollTop($('#chatBody')[0].scrollHeight);
            }

            if ($('#chatBody').length) {
                scrollToBottom();
            }

            var amount = parseFloat("{{ $order->total }}");
            var gateway, minAmount, maxAmount;

            $('.amount').on('input', function(e) {
                amount = parseFloat($(this).val());
                if (!amount) {
                    amount = 0;
                }
                calculation();
            });

            $('.gateway-input').on('change', function(e) {
                gatewayChange();
            });

            function gatewayChange() {
                let gatewayElement = $('.gateway-input:checked');
                let methodCode = gatewayElement.val();

                gateway = gatewayElement.data('gateway');
                minAmount = gatewayElement.data('min-amount');
                maxAmount = gatewayElement.data('max-amount');

                let percentCharge = gateway?.percent_charge || 0;
                let fixedCharge = gateway?.fixed_charge || 0;

                let processingFeeInfo =
                    `${parseFloat(percentCharge).toFixed(2)}% with ${parseFloat(fixedCharge).toFixed(2)} {{ __(gs('cur_text')) }} charge for payment gateway processing fees`;
                $(".proccessing-fee-info").attr("data-bs-original-title", processingFeeInfo);
                calculation();
            }

            gatewayChange();

            $(".more-gateway-option").on("click", function(e) {
                let paymentList = $(".gateway-option-list");
                paymentList.find(".gateway-option").removeClass("d-none");
                $(this).addClass('d-none');
                paymentList.animate({
                    scrollTop: (paymentList.height() - 60)
                }, 'slow');
            });

            function calculation() {
                if (!gateway) return;
                $(".gateway-limit").text(minAmount + " - " + maxAmount);

                let percentCharge = 0;
                let fixedCharge = 0;
                let totalPercentCharge = 0;

                if (amount) {
                    percentCharge = parseFloat(gateway.percent_charge);
                    fixedCharge = parseFloat(gateway.fixed_charge);
                    totalPercentCharge = parseFloat(amount / 100 * percentCharge);
                }

                let totalCharge = parseFloat(totalPercentCharge + fixedCharge);
                let totalAmount = parseFloat((amount || 0) + totalPercentCharge + fixedCharge);

                $(".final-amount").text(totalAmount.toFixed(2));
                $(".processing-fee").text(totalCharge.toFixed(2));
                $("input[name=currency]").val(gateway.currency);
                $(".gateway-currency").text(gateway.currency);

                if (amount < Number(gateway.min_amount) || amount > Number(gateway.max_amount)) {
                    $(".deposit-form button[type=submit]").attr('disabled', true);
                } else {
                    $(".deposit-form button[type=submit]").removeAttr('disabled');
                }

                if (gateway.currency != "{{ gs('cur_text') }}" && gateway.method.crypto != 1) {
                    $('.deposit-form').addClass('adjust-height')

                    $(".gateway-conversion, .conversion-currency").removeClass('d-none');
                    $(".gateway-conversion").find('.deposit-info__input .text').html(
                        `1 {{ __(gs('cur_text')) }} = <span class="rate">${parseFloat(gateway.rate).toFixed(2)}</span>  <span class="method_currency">${gateway.currency}</span>`
                    );
                    $('.in-currency').text(parseFloat(totalAmount * gateway.rate).toFixed(gateway.method.crypto == 1 ?
                        8 : 2))
                } else {
                    $(".gateway-conversion, .conversion-currency").addClass('d-none');
                    $('.deposit-form').removeClass('adjust-height')
                }

                if (gateway.method.crypto == 1) {
                    $('.crypto-message').removeClass('d-none');
                } else {
                    $('.crypto-message').addClass('d-none');
                }
            }

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            $('.gateway-input').change();


        })(jQuery);
    </script>
@endpush
