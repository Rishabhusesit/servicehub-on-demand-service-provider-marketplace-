@php
    $subscribeContent = getContent('subscribe.content', true);
@endphp

<section class="newsletter my-120">
    <div class="container custom-container">
        <div class="flex-between newsletter__wrapper">
            <form class="subscribeForm newsletter-form">
                <h2 class="newsletter-form__title">{{ __($subscribeContent?->data_values?->title) }}</h2>
                <div class="newsletter-form__group">
                    <div class="input-group w-100">
                        <input type="email" class="form-control form--control" name="subscribe" placeholder="{{ __($subscribeContent?->data_values?->placeholder_text) }}" required>
                        <button class="btn btn--base"><i class="las la-arrow-right"></i></button>
                    </div>
                </div>
            </form>
            <div class="newsletter-content">
                <div class="section-heading style-left">
                    <div>
                        <h2 class="section-heading__title">{{ __($subscribeContent?->data_values?->heading) }}</h2>
                        <p class="section-heading__desc">{{ __($subscribeContent?->data_values?->subheading) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
