@extends($activeTemplate.'layouts.master')
@section('content')

    <div class="profile-header flex-between">
        <h5 class="profile-header__title">{{ __($pageTitle) }}</h5>
    </div>
    <div class="profile-content pt-60">
        <div class="row gy-4">
            @foreach ($offers as $offer)
            <div class="col-md-6 col-xxl-4">
                <div class="offer-card" data-end-date="{{ $offer->end_date }}" data-offer-id="{{ $offer->id }}">
                    <div class="offer-card-top">
                        <h4 class="offer-card__cupon">{{ __($offer->name) }}</h4>
                        <h5 class="offer-card__title">@lang('Best Deal') {{ getAmount($offer->amount) }} {{ $offer->discount_type == 1 ? gs()->cur_text : '%' }} @lang('Off')</h5>
                    </div>
                    <div class="offer-card-body">
                        <p class="offer-card__text">{{ __($offer->description) }}</p>
                        <ul class="offer-card__list">
                            <li class="offer-card__listitem">
                                <span class="icon"><i class="las la-hourglass"></i></span>
                                <p class="list-text"> <strong>@lang('Validity'):</strong> {{ showDateTime($offer->end_date, 'd M, Y') }}</p>
                            </li>
                            <li class="offer-card__listitem">
                                <span class="icon"><i class="las la-stopwatch"></i></span>
                                <p class="list-text">
                                    <strong class="base" id="countdown_{{ $offer->id }}"></strong>
                                </p>
                            </li>
                        </ul>
                        <div class="offer-card-button">
                            <a href="{{ route('service') }}" class="btn--md btn-outline--base w-100 text-center" type="submit">@lang('Get Offer')</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

@endsection

@push('style')
<style>
    .offer-card.expired {
        filter: grayscale(100%);
        pointer-events: none;
        opacity: 0.7;
    }

    .offer-card.expired .btn--md {
        background-color: #ccc;
        cursor: not-allowed;
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
            document.addEventListener('DOMContentLoaded', function() {

                function updateCountdown(endDate, offerId, offerCard) {
                    var now = new Date();
                    var difference = endDate - now;

                    if (difference <= 0) {
                        document.getElementById('countdown_' + offerId).textContent = "Offer expired";
                        offerCard.classList.add('expired');
                        return;
                    }

                    var days = Math.floor(difference / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((difference % (1000 * 60)) / 1000);

                    document.getElementById('countdown_' + offerId).textContent = days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's';
                }

                var offerCards = document.querySelectorAll('.offer-card');

                offerCards.forEach(function(offerCard) {
                    var endDate = new Date(offerCard.getAttribute('data-end-date'));
                    var offerId = offerCard.getAttribute('data-offer-id');

                    updateCountdown(endDate, offerId, offerCard);

                    setInterval(function() {
                        updateCountdown(endDate, offerId, offerCard);
                    }, 1000);
                });
            });
    })(jQuery);
</script>
@endpush
