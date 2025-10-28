@php
    $bannerContent = getContent('banner.content', true);
    $bannerElement = getContent('banner.element', orderById: true);
@endphp


<section class="banner-section">
    <div class="container custom-container">
        <div class="row gy-5 align-items-center">
            <div class="col-lg-7">
                <div class="banner-content">
                    <div class="banner-content__shape d-none d-lg-block">
                        <img src="{{ asset($activeTemplateTrue . 'images/shapes/shape.png') }}" alt="@lang('shape')">
                    </div>
                    <h1 class="banner-content__title highlight" data-length="6">{{ __($bannerContent?->data_values?->heading) }}</h1>
                    <p class="banner-content__desc">{{ __($bannerContent?->data_values?->subheading) }}</p>

                    <div class="banner-content__search-area">
                        <form action="{{ route('service') }}" id="searchForm">
                            <div class="input-group">
                                <input type="text" class="form-control form--control" name="search" placeholder="{{ __($bannerContent?->data_values?->search_text) }}" value="{{ request()->search }}">
                                <button type="submit" class="btn input-group-text fs-20"><i class="las la-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="trusted-client">
                        <p class="trusted-client__title">{{ __($bannerContent?->data_values?->title_one) }}</p>
                        <div class="flex-align trusted-client__wrapper">
                            @foreach ($bannerElement as $element)
                                <div class="trusted-item">
                                    <img src="{{ frontendImage('banner', $element?->data_values?->logo, '115x30') }}" alt="@lang('trusted-client')">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex-align gap-3">
                        <div class="customer-wrapper flex-align">
                            @foreach ($bannerElement as $element)
                                <div class="customer-wrapper__item">
                                    <img src="{{ frontendImage('banner', $element?->data_values?->user_image, '54x54') }}" alt="@lang('customer')">
                                </div>
                            @endforeach
                            <div class="customer-wrapper__item">
                                <p class="customer-count fs-20 fw-bold">
                                    <span>{{ __($bannerContent?->data_values?->count) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="customer-wrapper-title">
                            {{ __($bannerContent?->data_values?->title_two) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="banner-thumb">
                    <div class="banner-thumb__image">
                        <img src="{{ frontendImage('banner', $bannerContent?->data_values?->image, '714x760') }}" alt="@lang('banner-image')">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        (function($) {
            "use strict";
            $(window).on('load', function(e) {
                let hightlightContent = $('.highlight');
                let content = hightlightContent.text();
                let splitContent = content.split(' ');
                let length = hightlightContent.data('length');
                let htmlContent = ``;
                for (let i = 0; i < splitContent.length; i++) {
                    if (i === (length - 1)) {
                        htmlContent += ' ' + `<span class="px-1">${splitContent[i]}</span>`
                    } else {
                        htmlContent += ' ' + splitContent[i];
                    }
                }
                hightlightContent.html(htmlContent);
            });

            $('#searchForm').on('submit', function(event) {
                var searchInput = $('[name=search]').val().trim();
                if (searchInput.length < 2) {
                    notify('error', 'Please enter at least two characters')
                    event.preventDefault();
                }
            });
        })(jQuery)
    </script>
@endpush
