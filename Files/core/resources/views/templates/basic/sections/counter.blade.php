@php
    $counterElement = getContent('counter.element', orderById: true);
@endphp

<section class="countdown py-60 my-120" id="counter">
    <div class="container">
        <div class="countdown-wrapper">
            @foreach ($counterElement as $element)
                <div class="countdown-item">
                    <h1 class="countdown-item__count">
                        <span class="odometer" data-odometer-final="{{ $element?->data_values?->counter_digit }}"></span> +
                    </h1>
                    <h4 class="countdown-item__title">{{ __($element?->data_values?->title) }}</h4>
                </div>
                <div class="count_devide"></div>
            @endforeach
        </div>
    </div>
</section>

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">
@endpush


@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
@endpush
