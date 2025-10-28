@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <!-- service banner -->
    <section class="service-banner bg-img"
             data-background-image="{{ getImage(getFilePath('coverImage') . '/' . $service->cover_image) }}">
        <div class="container custom-container">
            <div class="service-banner__content">
                <h2 class="service-banner__title">{{ __($service->name) }}</h2>
                <div class="flex-align gap-2 ">
                    <!-- rating -->
                    <ul class="rating-list">
                        @php
                            $averageRating = $service->average_rating ?? ($service?->service?->average_rating ?? 0);
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            <li class="rating-list__item">
                                <span class="rating-list__text">
                                    @if ($i <= floor($averageRating))
                                        <i class="fa fa-star"></i>
                                    @elseif ($i == ceil($averageRating) && $averageRating - floor($averageRating) >= 0.5)
                                        <i class="fa fa-star-half-alt"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                </span>
                            </li>
                        @endfor
                    </ul>



                    <!-- flex -->
                    <div class="flex-align service-banner__text">
                        <p>(<span class="count">{{ __(round($averageRating, 1)) }}</span>
                            @lang('Rating'))</p>
                        <span class="dot">•</span>
                        <p><span>{{ __(round($service->total_rating ?? $service?->service?->total_rating, 1)) }}</span>
                            @lang('Reviews') </p>
                        <span class="dot">•</span>
                        <p><span class="count">{{ $service->serviceOptions()->count() ?? '' }}</span> @lang('Services')
                        </p>
                    </div>
                </div>
                @if ($service->note)
                    <div class="service-banner-wrapper">
                        <ul class="service-banner-list">
                            @foreach (explode(',', $service->note) as $note)
                                <li class="service-banner-list__item">
                                    <div class="service-banner-list__image">
                                        <img src="{{ asset($activeTemplateTrue . 'images/service_icon.png') }}"
                                             alt="">
                                    </div>
                                    <p class="service-banner-list__text">{{ __($note) }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- service details -->
    <section class="service-details pb-120 pt-60">
        <div class="container custom-container">
            <div class="service-details__wrapper">
                <div class="service-overview">
                    @if ($service->overview && $service->overview !== '<br>')
                        <h4 class="service-overview__title">@lang('Service Overview')</h4>
                        <div class="service-overview__col">
                            @php echo $service->overview @endphp
                        </div>
                    @endif

                    {{-- Faq --}}
                    @if ($service->faqs->isNotEmpty())
                        <div class="accordion custom--accordion mb-5" id="accordionExample">
                            <h4 class="service-overview__title">@lang('Faq')</h4>
                            @foreach ($service->faqs as $k => $faq)
                                <div class="accordion-item ">
                                    <h5 class="accordion-header" id="heading{{ $loop->index }}">
                                        <button class="accordion-button {{ $k == 0 ? '' : 'collapsed' }}" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}"
                                                aria-expanded="{{ $k == 0 ? 'true' : 'false' }}">
                                            {{ $faq['title'] }}
                                        </button>
                                    </h5>
                                    <div id="collapse{{ $loop->index }}"
                                         class="accordion-collapse collapse @if ($k == 0) show @endif"
                                         aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <p class="text">
                                                {{ $faq['description'] }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                    @endif
                    {{-- Faq --}}

                    <div class="service-overview__col">
                        <h5 class="service-overview__steptitle">@lang('How to order ?') </h5>
                        <ul class="service-overview__step">
                            @foreach (gs('order_steps') ?? [] as $step)
                                <li class="item">
                                    <div class="content">
                                        <h6 class="heading">{{ isset($step['title']) ? $step['title'] : '' }}</h6>
                                        <p>{{ isset($step['description']) ? $step['description'] : '' }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @if ($service->details && $service->details !== '<br>')
                        <div class="service-overview__col">
                            <h4 class="service-overview__title">@lang('Details')</h4>
                            <div class="service-overview__col">
                                @php echo $service->details @endphp
                            </div>
                        </div>
                    @endif
                </div>
                <!-- service box -->
                <div class="service-box">
                    <h5 class="service-box__title">@lang('Select Service')</h5>
                    @if ($service->serviceOptions->isNotEmpty())
                        @foreach ($service->serviceOptions as $option)
                            <div class="item">
                                <h5 class="item__heading">{{ __(strLimit(strip_tags($option->name), 42)) }}</h5>
                                <div class="item__button">
                                    <button type="button" class="btn btn--base viewBtn" data-option="{{ $option->id }}" data-bs-target="#serviceModal" data-bs-toggle="modal">
                                        <i class="las la-angle-right"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="item">
                            <h5 class="item__heading">@lang('No Service Available')</h5>
                            <div class="item__button">
                                <button type="button" class="btn btn--base disabled"><i
                                       class="las la-angle-right"></i></button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- modal -->
    <div class="modal fade service-modal" id="serviceModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal--header">
                    <h5 class="modal-title text-center service_name">{{ __($service->name) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
                </div>
                <div class="modal--body loadingModal">
                    <div class="row g-0">
                        <div class="col-md-7 modal--contents">

                        </div>
                        <div class="col-md-5">
                            <form action="{{ route('user.checkout') }}" class="h-100">
                                <div class="package-cart">
                                    <div class="service-package-cart">

                                    </div>
                                    <div class="row mx-2 mb-2 subTotal d-none">
                                        <hr>
                                        <div class="col-6 text-left">@lang('Subtotal')</div>
                                        <div class="col-6 text-right package-cart-price subTotalPrice"></div>
                                    </div>
                                    <button class="btn btn--base package-checkout disabled">@lang('Proceed To Checkout')<span><i class="las la-arrow-right"></i></span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style')
    <style>
        .old-price {
            text-decoration: line-through;
            color: #888;
        }

        .discount-price {
            font-weight: bold;
        }

        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loading img {
            width: 100px;
            height: 100px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";

            function fetchOptionDetails(optionId) {
                $.ajax({
                    url: '{{ route('services.fetch') }}',
                    method: 'GET',
                    data: {
                        optionId: optionId
                    },
                    beforeSend: function() {
                        $('.modal--contents').html(`
                <div class="loading">
                    <img src="{{ asset($activeTemplateTrue . 'images/loading.gif') }}" alt="Loading...">
                </div>
            `);
                    },
                    success: function(response) {
                        $('.modal--contents').html(response.content);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            $(document).on('click', '.item .viewBtn', function(e) {
                e.preventDefault();
                var optionId = $(this).data('option');
                fetchOptionDetails(optionId);
                $('#serviceModal').modal('show');
            });

            $(document).on('click', '.nextBtn', function(e) {
                e.preventDefault();
                var optionId = $(this).data('option');
                fetchOptionDetails(optionId);
            });

            $('.modal--contents').on('click', '.backBtn', function(e) {
                e.preventDefault();
                var optionId = $(this).data('option');

                $.ajax({
                    url: '{{ route('services.fetchParentOption') }}',
                    method: 'GET',
                    data: {
                        optionId: optionId
                    },
                    beforeSend: function() {
                        $('.modal--contents').html(`
                <div class="loading">
                    <img src="{{ asset($activeTemplateTrue . 'images/loading.gif') }}" alt="Loading...">
                </div>
            `);
                    },
                    success: function(response) {
                        $('.modal--contents').html(response.content);
                        $('#serviceModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            var addedOptions = {};

            function isCartEmpty() {
                return Object.keys(addedOptions).length === 0;
            }

            function updateCheckoutButtonState() {
                var checkoutButton = $('.package-checkout');
                var subTotal = $('.subTotal');

                if (isCartEmpty()) {
                    checkoutButton.addClass('disabled');
                    subTotal.addClass('d-none');
                } else {
                    checkoutButton.removeClass('disabled');
                    subTotal.removeClass('d-none');
                }

                updateSubTotal();
            }

            updateCheckoutButtonState();

            $('.modal--contents').on('click', '.addBtn', function(e) {
                e.preventDefault();

                var optionData = $(this).data('option');
                var service = optionData.service;
                var option = optionData.option;
                var price = optionData.price;

                var existingOption = addedOptions[option.id];

                if (existingOption) {
                    existingOption.quantity++;
                    updateQuantity(existingOption.id, existingOption.quantity);
                } else {
                    var serviceHTML = `
            <div class="item" data-id="${option.id}">
                <div class="item__wrapper">
                    <p class="title">${service.name}</p>
                    <div class="item__right">
                        <div class="quantity ms-auto">
                            <button class="quantity__btn minus"><i class="las la-minus"></i></button>
                            <input class="quantity__input" readonly type="number" value="1" name="quantity[]">
                            <span class="quantity__text"></span>
                            <button class="quantity__btn plus"><i class="las la-plus"></i></button>
                        </div>
                        <p class="package-cart-price">{{ gs('cur_sym') }}${price} {{ gs('cur_text') }}</p>
                    </div>
                </div>
                <p class="item__text">${option.name}</p>
                <input type="hidden" name="option_id[]" value="${option.id}">
            </div>
        `;

                    $('.package-cart .service-package-cart').append(serviceHTML);

                    addedOptions[option.id] = {
                        id: option.id,
                        quantity: 1,
                        price: price
                    };
                }

                $('.quantity__btn').on('click', function(e) {
                    e.preventDefault();
                });

                updateCheckoutButtonState();
            });

            function updateQuantity(optionId, quantity) {
                var item = $('.package-cart .service-package-cart').find('.item[data-id="' + optionId + '"]');
                var quantityInput = item.find('.quantity__input');
                var priceElement = item.find('.package-cart-price');
                var option = addedOptions[optionId];

                option.quantity = quantity;
                quantityInput.val(quantity);
                var totalPrice = option.price * quantity;
                priceElement.text('{{ gs('cur_sym') }} ' + totalPrice.toFixed(2));

                if (quantity === 0) {
                    removeOption(optionId);
                }

                updateSubTotal();
            }

            function removeOption(optionId) {
                $('.package-cart .service-package-cart').find('.item[data-id="' + optionId + '"]').remove();
                delete addedOptions[optionId];
                updateCheckoutButtonState();
            }

            $('.package-cart .service-package-cart').on('click', '.plus', function() {
                var optionId = $(this).closest('.item').data('id');
                addedOptions[optionId].quantity++;
                updateQuantity(optionId, addedOptions[optionId].quantity);
            });

            $('.package-cart .service-package-cart').on('click', '.minus', function() {
                var optionId = $(this).closest('.item').data('id');
                if (addedOptions[optionId].quantity > 1) {
                    addedOptions[optionId].quantity--;
                    updateQuantity(optionId, addedOptions[optionId].quantity);
                } else {
                    removeOption(optionId);
                }
            });

            function updateSubTotal() {
                var subtotal = 0;
                for (var key in addedOptions) {
                    subtotal += addedOptions[key].price * addedOptions[key].quantity;
                }
                $('.subTotalPrice').text('{{ gs('cur_sym') }}' + subtotal.toFixed(2) + ' {{ gs('cur_text') }}');
            }
        })(jQuery);
    </script>
@endpush
