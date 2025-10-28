@php
    $aboutContent = getContent('about.content', true);
    $aboutElement = getContent('about.element', orderById: true);
@endphp

<div class="solution-section my-120">
    <div class="container custom-container">
        <div class="row g-4 g-md-5">
            <div class="col-xl-5 col-lg-6 col-md-12">
                <div class="solution-thumb">
                    <img src="{{ frontendImage('about', $aboutContent?->data_values?->image, '475x395') }}"
                         alt="@lang('image')">
                </div>
            </div>
            <div class="col-xl-7 col-lg-6 col-md-12">
                <div class="solution-content">
                    <div class="section-heading style-left">
                        <h2 class="section-heading__title">{{ __($aboutContent?->data_values?->heading) }}</h2>
                    </div>
                    <ul class="solution-list">
                        @foreach ($aboutElement as $element)
                            <li class="solution-item">
                                <span class="solution-item__icon"> @php echo $element?->data_values?->icon; @endphp</span>
                                <div class="solution-item__content">
                                    <h5 class="solution-item__title">{{ __($element?->data_values?->title) }}</h5>
                                    <p class="solution-item-des">{{ __($element?->data_values?->subtitle) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="flex-align solution-content__btn">
                        <a href="{{ $aboutContent?->data_values?->left_button_link }}"
                           class="btn--md btn btn--base fs-18">{{ __($aboutContent?->data_values?->left_button_text) }}<span
                                  class="icon"><i class="las la-arrow-right"></i></span>
                        </a>
                        <a href="{{ $aboutContent?->data_values?->right_button_link }}"
                           class="btn--md btn btn-outline--base fs-18">{{ __($aboutContent?->data_values?->right_button_text) }}
                            <span class="icon"><i class="las la-arrow-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
