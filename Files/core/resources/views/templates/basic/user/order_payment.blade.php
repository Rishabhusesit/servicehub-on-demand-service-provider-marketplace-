@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="order-confirmation my-60">
        <div class="container custom-container">
            <div class="row gy-5">
                <div class="col-xl-8">
                    @if ($order->payment_type == Status::COD_PAYMENT)
                        <div class="order-card">
                            <div class="order-card__image">
                                <img src="{{ asset($activeTemplateTrue . '/images/success.png') }}" alt="@lang('image')">
                            </div>
                            <div class="content">
                                <h4 class="order-card__title">{{ $user->fullname }} @lang('your order has been placed successfully!')</h4>
                                <p class="order-card__text">@lang('Your Order ID') : {{ $order->order_id }}</p>
                                <div class="order-card__button flex-wrap">
                                    <a href="{{ route('user.order.details', $order->id) }}"
                                       class="btn--md btn btn--base">@lang('Order Details')</a>
                                    <a href="{{ route('home') }}"
                                       class="btn--md btn btn-outline--base">@lang('Go Home')</a>
                                </div>


                                <div class="order-card__list">
                                    <h5 class="title">@lang('What is next :')</h5>
                                    <ol class="list-inner">
                                        <li class="list-item">@lang('You’ll get a call for confirmation.')</li>
                                        <li class="list-item">@lang('We will monitor the whole service.')</li>
                                        <li class="list-item">@lang('Expert will arrive at your place & ensure the best service.')</li>
                                        <li class="list-item">@lang('So just RELAX. For any further query contact us.')</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="order-card">
                            @if ($order->status != Status::ORDER_CANCEL)
                                @if ($order->payment_status == Status::PAID)
                                    <div class="summery-block">
                                        <div class="summery-block">
                                            <div class="payment-success flex-align">
                                                <div class="payment-success__image">
                                                    <img src="{{ asset($activeTemplateTrue . '/images/success.png') }}"
                                                         alt="success">
                                                </div>
                                                <h5 class="payment-success__heading">@lang('Payment completed successfully')</h5>
                                            </div>

                                            <div class="content">
                                                <p class="order-card__text">@lang('Your Order ID') : {{ $order->order_id }}</p>
                                                <div class="order-card__button flex-wrap">
                                                    <a href="{{ route('user.order.details', $order->id) }}"
                                                       class="btn--md btn btn--base">
                                                        @lang('Order Details')
                                                    </a>
                                                    <a href="{{ route('home') }}" class="btn--md btn btn-outline--base">
                                                        @lang('Go Home')
                                                    </a>
                                                </div>

                                                <div class="order-card__list">
                                                    <h5 class="title">@lang('What is next :')</h5>
                                                    <ol class="list-inner">
                                                        <li class="list-item">@lang('You’ll get a call for confirmation.')</li>
                                                        <li class="list-item">@lang('We will monitor the whole service.')</li>
                                                        <li class="list-item">@lang('Expert will arrive at your place & ensure the best service.')</li>
                                                        <li class="list-item">@lang('So just RELAX. For any further query contact us.')</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="summery-block w-100">
                                        <h5 class="summery-box__title">@lang('Make Payment')</h5>
                                        <form action="{{ route('user.deposit.insert', $order->id) }}" method="post"
                                              class="deposit-form">
                                            @csrf
                                            <input type="hidden" name="currency">
                                            <div class="gateway-card p-0 border-0">
                                                <div class="row gy-4 justify-content-center">
                                                    <div class="col-lg-6">
                                                        <div class="payment-system-list is-scrollable gateway-option-list">
                                                            @foreach ($gatewayCurrency as $data)
                                                                <label for="{{ titleToKey($data->name) }}"
                                                                       class="payment-item @if ($loop->index > 4) d-none @endif gateway-option">
                                                                    <span class="payment-check d-none">
                                                                        <input class="payment-item__radio gateway-input"
                                                                               id="{{ titleToKey($data->name) }}"
                                                                               type="radio" name="gateway"
                                                                               value="{{ $data->method_code }}"
                                                                               data-gateway='{{ json_encode($data) }}'
                                                                               data-min-amount="{{ showAmount($data->min_amount) }}"
                                                                               data-max-amount="{{ showAmount($data->max_amount) }}"
                                                                               @checked(old('gateway', $loop->first) == $data->method_code)>
                                                                    </span>
                                                                    <span class="payment-item__right">
                                                                        <span class="payment-item__info">
                                                                            <span class="payment-item__check"></span>
                                                                            <span
                                                                                  class="payment-item__name">{{ __($data->name) }}</span>
                                                                        </span>
                                                                        <span class="payment-item__thumb">
                                                                            <img class="payment-item__thumb-img"
                                                                                 src="{{ getImage(getFilePath('gateway') . '/' . $data?->method?->image) }}"
                                                                                 alt="@lang('payment-thumb')">
                                                                        </span>
                                                                    </span>
                                                                </label>
                                                            @endforeach
                                                            @if ($gatewayCurrency->count() > 4)
                                                                <button type="button"
                                                                        class="payment-item__btn more-gateway-option text--base">
                                                                    @lang('See More')
                                                                    <span class="icon">
                                                                        <i class="la la-arrow-down"></i>
                                                                    </span>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <!-- Payment Information -->
                                                    <div class="col-lg-6">
                                                        <div class="deposit-info">
                                                            <div class="deposit-info__title">
                                                                <p class="text has-icon"> @lang('Amount')
                                                                    <span></span>
                                                                </p>
                                                            </div>
                                                            <div class="deposit-info__input">
                                                                <p class="text"><span>{{ showAmount($order->total) }}</span></p>
                                                            </div>
                                                        </div>
                                                        <div class="deposit-info">
                                                            <div class="deposit-info__title">
                                                                <p class="text has-icon"> @lang('Limit')
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
                                                                <p class="text has-icon">@lang('Processing Charge')
                                                                    <span data-bs-toggle="tooltip"
                                                                          title="@lang('Processing charge for payment gateways')"
                                                                          class="proccessing-fee-info"><i
                                                                           class="las la-info-circle"></i> </span>
                                                                </p>
                                                            </div>
                                                            <div class="deposit-info__input">
                                                                <p class="text"><span
                                                                          class="processing-fee">@lang('0.00')</span>
                                                                    {{ __(gs('cur_text')) }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class="deposit-info total-amount pt-3">
                                                            <div class="deposit-info__title">
                                                                <p class="text">@lang('Total')</p>
                                                            </div>
                                                            <div class="deposit-info__input">
                                                                <p class="text"><span
                                                                          class="final-amount">@lang('0.00')</span>
                                                                    {{ __(gs('cur_text')) }}</p>
                                                            </div>
                                                        </div>
                                                        <div
                                                             class="deposit-info gateway-conversion d-none total-amount pt-2">
                                                            <div class="deposit-info__title">
                                                                <p class="text">@lang('Conversion')
                                                                </p>
                                                            </div>
                                                            <div class="deposit-info__input">
                                                                <p class="text"></p>
                                                            </div>
                                                        </div>
                                                        <div
                                                             class="deposit-info conversion-currency d-none total-amount pt-2">
                                                            <div class="deposit-info__title">
                                                                <p class="text">
                                                                    @lang('In') <span
                                                                          class="gateway-currency"></span>
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
                                                            <button type="submit" class="btn btn--md btn--base w-100"
                                                                    disabled>
                                                                @lang('Make Payment')
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                        </div>
                    @endif
                    @endif
                </div>
                <div class="col-xl-4">
                    <div class="summery-box">
                        <h5 class="summery-box__title">@lang('Payment')</h5>
                        <div class="accordion">
                            <div class=" summery-block__accordion mb-0">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#summery">
                                    @lang('Total Payable') <span class="total-price"> {{ showAmount($order->total) }}</span>
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


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>


.deposit-info__input .text, .deposit-info__input .text span{
    color: hsl(var(--black));
    font-weight: 500;
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

@push('script')
    <script>
        "use strict";
        (function($) {

            var amount = parseFloat("{{ $order->total }}");
            var gateway, minAmount, maxAmount;


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
