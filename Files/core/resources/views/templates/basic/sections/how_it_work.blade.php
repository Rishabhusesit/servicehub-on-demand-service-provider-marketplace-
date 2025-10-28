@php
    $howItWorkContent = getContent('how_it_work.content', true);
    $howItWorkElements = getContent('how_it_work.element', orderById: true);
@endphp

<section class="service-step my-120">
    <div class="container custom-container">
        <div class="section-heading style-left">
            <h2 class="section-heading__title">{{ __($howItWorkContent?->data_values?->heading) }}</h2>
        </div>
        <div class="row gy-5">
            <div class="col-lg-6 col-xl-5">
                <div class="service-step-thumbs">
                    <img src="{{ frontendImage('how_it_work', $howItWorkContent?->data_values?->image, '525x380') }}"
                         alt="@lang('service')">
                </div>
            </div>
            <div class="col-lg-6 col-xl-7">
                <div class="service-step-content">
                    <ul class="service-step-list">
                        @foreach ($howItWorkElements as $element)
                            <li class="service-step-item flex-wrap">
                                <div class="service-step-item__content">
                                    <h5 class="service-step-item__title">{{ __($element?->data_values?->title) }}</h5>
                                    <p class="service-step-item__desc">{{ __($element?->data_values?->subtitle) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
