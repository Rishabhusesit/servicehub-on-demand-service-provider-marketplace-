@extends($activeTemplate . 'layouts.frontend')
@section('content')

<section class="service-complete py-120">
    <div class="container custom-container">
        <div class="service-complete__wrapper">
            <div class="service-complete__content">
                <div class="service-complete__image">
                    <img src="{{ asset($activeTemplateTrue . 'images/success.png') }}" alt="images">
                </div>
                <h3 class="service-complete__title">{{ __($order?->user?->fullname) }} @lang('your service is completed successfully!')</h3>
                <div class="flex-align justify-content-center gap-3">
                    <a href="{{ route('user.order.review', $order->id) }}" class="btn--md btn btn--base fs-18">@lang('Write a Review')</a>
                    <a href="{{ route('user.home') }}" class="btn--md btn btn-outline--base fs-18">@lang('Cancel')</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection