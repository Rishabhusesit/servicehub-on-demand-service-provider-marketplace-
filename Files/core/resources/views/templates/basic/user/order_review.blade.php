@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="review">
        <div class="container custom-container py-120">
            <div class="review-conetnt">
                <div class="section-heading">
                    <h3 class="section-heading__title">@lang('Give a review and say about your exprience')</h3>
                </div>
                <div class="row gy-5">
                    <div class="col-lg-12">
                        <div class="row gy-4">
                            <div class="col-lg-6">
                                <div class="review-block mb-0">
                                    <h5 class="review-block__title">@lang('About Service')</h5>
                                    <div class="product-card">
                                        <div class="product-card__image">
                                            <img src="{{ getImage(getFilePath('service') . '/' . $serviceName?->image, getFileSize('service')) }}"
                                                 alt="@lang('image')">
                                        </div>
                                        <div class="product-card__content">
                                            <h5 class="product-card__heading">{{ __($serviceName?->name) }}</h5>
                                            <p class="product-card__text">@lang('Order ID') : #{{ $order->order_id }}</p>
                                            <p class="product-card__price">{{ showAmount($order->total) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="review-block mb-0">
                                    <h5 class="review-block__title">@lang('About Service Provider')</h5>
                                    <div class="provider-profile">
                                        <div class="provider-profile__wrapper">
                                            <div class="provider-profile__image">
                                                <img src="{{ getImage(getFilePath('providerProfile') . '/' . $order?->provider?->image, getFileSize('providerProfile')) }}"
                                                     alt="@lang('image')">
                                            </div>
                                            <div class="content">
                                                <h5 class="provider-profile__designation">
                                                    {{ __($order?->provider?->fullname) }}</h5>
                                                <p class="provider-profile__name">{{ __($order?->provider?->address) }}</p>
                                                <p class="flex-align provider-profile__tel"><span class="icon"><i
                                                           class="las la-phone"></i></span>({{ $order?->provider?->dial_code }}){{ $order?->provider?->mobile }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mt-lg-0">
                            <a href="{{ route('user.home') }}" class="btn btn--md btn--base">@lang('Back to Dashboard')</a>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <form action="{{ route('user.order.review.submit', $order->id) }}" method="post"
                              class="review-form">
                            @csrf
                            <h5 class="review-form__title">@lang('Give Review')</h5>
                            <div class="rating_wrapper flex-align gap-2">
                                <div class="rating-group">
                                    <!-- Star rating inputs -->
                                    <input class="rating__input" name="rating" id="rating-0" value="0" type="radio"
                                           disabled checked>
                                    <label aria-label="1 star" class="rating__label" for="rating-1"><i
                                           class="rating__icon--star fa fa-star"></i></label>

                                    <input class="rating__input" name="rating" id="rating-1" value="1"
                                           type="radio">
                                    <label aria-label="2 stars" class="rating__label" for="rating-2"><i
                                           class="rating__icon--star fa fa-star"></i></label>

                                    <input class="rating__input" name="rating" id="rating-2" value="2"
                                           type="radio">
                                    <label aria-label="3 stars" class="rating__label" for="rating-3"><i
                                           class="rating__icon--star fa fa-star"></i></label>

                                    <input class="rating__input" name="rating" id="rating-3" value="3"
                                           type="radio">
                                    <label aria-label="4 stars" class="rating__label" for="rating-4"><i
                                           class="rating__icon--star fa fa-star"></i></label>

                                    <input class="rating__input" name="rating" id="rating-4" value="4"
                                           type="radio">
                                    <label aria-label="5 stars" class="rating__label" for="rating-5"><i
                                           class="rating__icon--star fa fa-star"></i></label>

                                    <input class="rating__input" name="rating" id="rating-5" value="5"
                                           type="radio">
                                </div>
                                <p><span id="current-rating">0</span> / <span>5</span></p>
                            </div>
                            <textarea class="form--control" placeholder="@lang('Tell us more...')" name="review" required></textarea>
                            <button type="submit" class="w-100 btn btn--md btn--base">@lang('Submit')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        "use strict";
        (function($) {
            $("input[name='rating']").on('change', function() {
                var rating = $("input[name='rating']:checked").val();
                $('#current-rating').text(rating);
            });

        })(jQuery);
    </script>
@endpush
