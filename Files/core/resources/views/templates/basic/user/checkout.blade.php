@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="my-120 placeorder-section">
        <div class="container custom-container">
            <form method="post" action="{{ route('user.order.place') }}">
                @csrf
                <div class="row gy-5">
                    <div class="col-xl-7">
                        <div class="order-card">
                            <div class="order-card__icon"><i class="las la-calendar-check"></i></div>
                            <div class="content">
                                <div class="order-card__header">
                                    <div class="order-card__header-left">
                                        <h5 class="order-card__title">@lang('Schedule')</h5>
                                        <p class="order-card__text">@lang('Expert will arrive at your given address within the time')</p>
                                    </div>
                                </div>
                                <div class="order-card__form">
                                    <div class="row gy-2">
                                        <div class="col-md-6 col-xl-6">
                                            <label class="form--label">@lang('Date')<span class="text--danger">*</span></label>
                                            <div class="input-group flex-nowrap input--group">
                                                <input type="text" class="date form--control" placeholder="mm/dd/yyy" required name="schedule_date" value="{{ old('schedule_date') }}" autocomplete="off" required>
                                                <label for="schedule_date" class="input-group-text"><i class="las la-calendar"></i></label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <label class="form--label">@lang('Time')<span class="text--danger">*</span></label>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="input-group input--group flex-nowrap">
                                                    <input type="text" class="form--control from-time" placeholder="00 AM" required name="from_time" value="{{ old('from_time') }}" required>
                                                    <label for="from_time" class="input-group-text"><i class="fa-solid fa-caret-down"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order-card">
                            <div class="order-card__icon"><i class="las la-user"></i></div>
                            <div class="content">
                                <div class="order-card__header">
                                    <div class="order-card__header-left">
                                        <h5 class="order-card__title">@lang('Contact Person')</h5>
                                        <p class="order-card__text">@lang('Expert will contact with the following person')</p>
                                    </div>
                                </div>
                                <div class="order-card__form">
                                    <div class="row gy-2">
                                        <div class="col-md-7">
                                            <label class="form--label">@lang('Name')</label>
                                            <input type="text" class="form--control" name="contact_person_name" value="{{ old('contact_person_name') }}">
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form--label">@lang('Number')</label>
                                            <input type="tel" class="form--control" name="contact_person_number" value="{{ old('contact_person_number') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order-card">
                            <div class="order-card__icon"><i class="las la-home"></i></div>
                            <div class="content">
                                <div class="order-card__header">
                                    <div class="order-card__header-left">
                                        <h5 class="order-card__title">@lang('Address')</h5>
                                        <p class="order-card__text">@lang('Expert will arrive at the address given below')</p>
                                    </div>
                                </div>

                                <form class="order-card__form">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="form--label">@lang('City')</label>
                                            <select class="form--control form-select select2 city-select" name="city_id" required>
                                                <option value="">@lang('Select City')</option>
                                                @foreach ($cities ?? [] as $city)
                                                    <option value="{{ $city->id }}" data-delivery_charge="{{ $city->delivery_charge }}" data-areas="{{ json_encode($city->areas) }}">{{ __($city->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 form-group">
                                            <label class="form--label">@lang('Area')</label>
                                            <select class="form--control form-select select2 area-select" name="area_id" required>
                                                <option value="">@lang('Select Area')</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 form-group">
                                            <label class="form--label">@lang('Address')</label>
                                            <input type="text" class="form--control" name="address" placeholder="@lang('Address')" value="{{ old('address') }}" required>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="summery-box">
                            <h5 class="summery-box__title">@lang('Order Summery')</h5>
                            <div class="summery-block">

                                <ul class="summery-list">
                                    @foreach ($options as $option)
                                        <li class="summery-list-item">
                                            <div>
                                                <strong class="summery-list-item__title mb-0">{{ $option['service'] }}</strong>
                                                <div class="summery-list-item__content">

                                                    @if ($option['parent'])
                                                        <span>{{ $option['parent'] }}</span>
                                                    @endif
                                                    <span>{{ $option['name'] }} x {{ $option['quantity'] }}</span>
                                                </div>
                                            </div>
                                            <span class="summery-list-item__price">{{ gs('cur_sym') }}{{ showAmount($option['price'] * $option['quantity'], currencyFormat: false) }}</span>
                                        </li>
                                    @endforeach
                                </ul>

                                <ul class="list">
                                    <li class="item">
                                        <p>@lang('Subtotal')</p>
                                        <span class="subtotal">{{ gs('cur_sym') }}{{ showAmount($subtotal, currencyFormat: false) }}</span>
                                    </li>
                                    <li class="item">
                                        <p>@lang('Delivery Charge')</p>
                                        <span class="delivery_charge">{{ gs('cur_sym') }}{{ showAmount(0, currencyFormat: false) }}</span>
                                    </li>

                                    <li class="item">
                                        <p>@lang('Discount')</p>
                                        <span class="discount">{{ gs('cur_sym') }}{{ showAmount(0, currencyFormat: false) }}</span>
                                    </li>
                                </ul>
                                <div class="summery-block__total">
                                    <strong>@lang('Amount to be paid')</strong>
                                    <strong class="total">{{ gs('cur_sym') }}{{ showAmount($subtotal, currencyFormat: false) }}</strong>
                                </div>
                            </div>

                            <div class="summery-block">
                                <div class="promo_inner">
                                    <form class="promo">
                                        @csrf
                                        <div class="input-group  flex-nowrap">
                                            <input type="text" class="form--control coupon_code" placeholder="@lang('Type your promo')" required>
                                            <button type="submit" class="promo_btn input-group-text">@lang('Add Promo')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="summery-block">
                                <div class="summery-block__title">
                                    <span class="icon"><i class="las la-edit"></i></span>
                                    <p class="text">@lang('Any additional notes?')</p>
                                </div>
                                <textarea class="form--control" name="note" placeholder="@lang('Type here...')">{{ old('note') }}</textarea>
                            </div>

                            <div class="summery-block">
                                <div class="summery-block__title">
                                    <span class="icon"><i class="las la-credit-card"></i></span>
                                    <p class="text">@lang('Payment Type')</p>
                                </div>
                                <div class="rounded bg-white p-4">
                                    <div class="flex-wrap gap-lg-3 gap-2">
                                        <div>
                                            <div class="custom--radio">
                                                <input type="radio" value="1" id="cash"
                                                       class="form-check-input" name="payment_type">
                                                <i class="las la-wallet"></i>
                                                <label for="cash"> @lang('Cash On Delivery')</label>
                                            </div>
                                        </div>

                                        <div class=" onlinePaymentCheckout">
                                            <div class="custom--radio">
                                                <input type="radio" id="onlinePayment" value="2" checked
                                                       class="form-check-input " name="payment_type">
                                                <i class="las la-money-check"></i>
                                                <label for="onlinePayment"> @lang('Online Payment')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="summery-block">
                                <p class="terms-condition">
                                    @if (gs('agree'))
                                        <div class="col-12">
                                            @php
                                                $policyPages = getContent('policy_pages.element', false, orderById: true);
                                            @endphp
                                            <div class="form-group">
                                                <input type="checkbox" id="agree" @checked(old('agree'))
                                                       name="agree" required>
                                                <label for="agree">@lang('By placing the order, I agree to the')</label>
                                                @foreach ($policyPages as $policy)
                                                    <a href="{{ route('policy.pages', $policy->slug) }}" target="_blank"
                                                       class="text--base">{{ __($policy?->data_values?->title) }}</a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach

                                            </div>
                                        </div>
                                    @endif
                                </p>
                                <button type="submit" class="btn btn--base w-100">@lang('Place Order')</button>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection



@push('style')
    <style>
        .ui-timepicker-wrapper {
            min-width: 200px;
            max-width: 308px;
            width: 100%;
        }

        @media screen and (max-width: 400px) {
            .ui-timepicker-wrapper {
                min-width: 200px;
                max-width: 250px;
            }

        }

        .remove-coupon {
            text-decoration: underline;
            font-weight: bold;
            margin-left: 5px;
            color: hsl(var(--danger));
        }

        .remove-coupon:hover {
            color: hsl(var(--danger));
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

        .summery-list-item__content {}


        .summery-block textarea.form--control {
            border-color: hsl(var(--border-color));
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid hsl(var(--border-color)) !important;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-inline: 0px !important;
        }

        .select2-container--default .select2-selection--single {
            padding: 8.5px 4px 8.5px 12px !important;
        }

        .promo_inner .form--control {
            border-color: hsl(var(--border-color));
        }

        .input--group .form--control {
            border-color: none;
        }

        .input--group .form--control:focus {
            border: none !important;
        }

        .input--group {
            border-radius: 8px;
            border: 1px solid hsl(var(--border-color));
        }

        .input--group:focus {
            border: 1px solid hsl(var(--base));
        }

        .input--group .input-group-text {
            padding: 10px 12px !important;
        }

        .order-card .input-group-text {
            border: transparent !important;
        }

        .input--group:has(.form--control:focus) {
            border: 1px solid hsl(var(--base));
        }

        .custom--radio label {
            font-weight: 500;
        }

        .custom--radio label {
            cursor: pointer;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            let subtotal = Number({{ $subtotal }});
            let deliveryCharge = 0;
            let discount = 0;
            let paidAmount = subtotal;
            let curSym = "{{ gs('cur_sym') }}";

            $('[name=city_id]').on('change', function(e) {
                e.preventDefault();
                deliveryCharge = Number($(this).find('option:selected').data('delivery_charge'));
                let areas = $(this).find('option:selected').data('areas');
                let areaOption = '<option value="" selected>Select Area</option>';
                $.each(areas, function(index, value) {
                    areaOption += `<option value="${value.id}">${value.name}</option>`;
                });
                $('[name=area_id]').html(areaOption);
                calculation()
            });

            function calculation() {
                paidAmount = subtotal + deliveryCharge - discount;
                $('.delivery_charge').text(`${curSym}${parseFloat(deliveryCharge).toFixed(2)}`);
                $('.discount').text(`${curSym}${parseFloat(discount).toFixed(2)}`);
                $('.total').text(`${curSym}${parseFloat(paidAmount).toFixed(2)}`);
            }


            $('.promo').on('submit', function(e) {
                e.preventDefault();
                const couponCode = $('.coupon_code').val();
                $.ajax({
                    url: "{{ route('user.apply.coupon') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        coupon_code: couponCode,
                    },
                    success: function(response) {
                        if (response.success) {
                            discount = response.discount;
                            calculation();
                            $('.promo').addClass('d-none');
                            let removeHtml = `<div class="applied-coupon"><strong class="text--warning">${couponCode} coupon applied successfully</strong><a href="javascript:void(0)" class="remove-coupon removeCoupon">Remove Coupon</a></div>`;
                            $('.promo_inner').append(removeHtml);

                            notify('success', response.message);
                        } else {
                            notify('error', response.message);
                        }
                    },
                });
            });

            $(document).on('click', '.removeCoupon', function(e) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.remove.coupon') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.promo').removeClass('d-none');
                            $('.coupon_code').val('');
                            $('.applied-coupon').remove();
                            discount = 0;
                            calculation();
                            notify('success', response.success);
                        } else {
                            notify('error', response.error);
                        }
                    }
                });
            })

        })(jQuery);
    </script>
@endpush
