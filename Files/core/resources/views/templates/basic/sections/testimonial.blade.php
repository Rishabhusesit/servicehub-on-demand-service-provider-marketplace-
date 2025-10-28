@php
    $testimonialContent = getContent('testimonial.content', true);
    $testimonialElement = getContent('testimonial.element', orderById: true);
@endphp

<section class="testimonials my-120">
    <div class="video-section">
        <div class='container custom-container pb-60'>
            <div class="row">
                <div class="col-12">
                    <div class="video-container">
                        <div class="video-container__image">
                            <img src="{{ frontendImage('testimonial', $testimonialContent?->data_values?->image, '1352x630') }}"
                                 alt="@lang('video-poster')">
                        </div>
                        <a href="{{ $testimonialContent?->data_values?->video_link }}" class="video-container__preview"
                           data-rel="lightcase">
                            <span class="video-preview__icon">
                                <i class="las la-play"></i>
                            </span>
                        </a>
                        <h2 class="video-container__text">{{ __($testimonialContent?->data_values?->image_text) }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container custom--container">
        <div class="testimonial-slider">
            @foreach ($testimonialElement as $testimonial)
                <div class="testimonails-card">
                    <div class="testimonial-item">
                        <div class="testimonial-thumb-wrapper">
                            <div class="testimonial-thumb">
                                <img src="{{ frontendImage('testimonial', $testimonial?->data_values?->image, '80x80') }}" alt="cover">
                            </div>
                        </div>
                        <p class="testimonial-item__desc">"{{ __($testimonial?->data_values?->review) }}"</p>
                        <div class="testimonial-item__content">
                            <div class="testimonial-item__info">
                                <div class="testimonial-item__details">
                                    <h5 class="testimonial-item__name"> {{ __($testimonial?->data_values?->name) }}</h5>
                                    <span class="testimonial-item__designation">
                                        {{ __($testimonial?->data_values?->designation) }} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/lightcase.css') }}">
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/lightcase.js') }}"></script>
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
@endpush

@push('style')
    <style>
        .video-container__preview:hover {
            color: hsl(var(--base));
        }
    </style>
@endpush
