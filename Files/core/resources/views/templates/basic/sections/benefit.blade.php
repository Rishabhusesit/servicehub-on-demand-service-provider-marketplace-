@php
    $benefitContent = getContent('benefit.content', true);
    $benefitElement = getContent('benefit.element', orderById: true);
@endphp

<div class="professional-benifits my-120">
    <div class="container custom-container">
        <div class="professional-benifits__wrapper">
            <div class="row">
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="professional-benifits__image">
                        <img src="{{ frontendImage('benefit', $benefitContent?->data_values?->image, '664x696') }}"
                            alt="@lang('img')">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="professional-benifits-content">
                        <div class="section-heading style-left">
                            <h4 class="section-heading__subtitle">{{ __($benefitContent?->data_values?->title) }}</h4>
                            <h2 class="section-heading__title">{{ __($benefitContent?->data_values?->heading) }}</h2>
                            <p class="section-heading__desc">{{ __($benefitContent?->data_values?->subheading) }}</p>
                        </div>

                        <div class="professional-benifits-list flex-wrap">
                            @foreach ($benefitElement as $element)
                                <div class="professional-benifits-item">
                                    <div class="professional-benifits-item__image">
                                        <img src="{{ frontendImage('benefit', $element?->data_values?->image, '64x64') }}"
                                            alt="@lang('img')">
                                    </div>
                                    <h6 class="professional-benifits-item__title">
                                        {{ __($element?->data_values?->title) }}</h6>
                                </div>
                            @endforeach
                        </div>

                        <div class="professional-benifits-content__button">
                            <a href="{{ $benefitContent?->data_values?->button_link }}"
                                class="btn--md btn btn--white fs-18">{{ __($benefitContent?->data_values?->button_text) }}<span
                                    class="icon"><i class="las la-arrow-right"></i></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
