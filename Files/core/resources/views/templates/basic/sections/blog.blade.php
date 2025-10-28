@php
    $blogContent = getContent('blog.content', true);
    $blogElement = getContent('blog.element', limit: 3, orderById: true);
@endphp


<section class="blog my-120">
    <div class="container custom-container">
        <div class="section-heading style-left flex-between align-items-end gap-2">
            <div>
                <h2 class="section-heading__title">{{ __($blogContent?->data_values?->heading) }}</h2>
                <h6 class="section-heading__desc">{{ __($blogContent?->data_values?->subheading) }}</h6>
            </div>
            <a href="{{ route('blog') }}" class="btn--md btn btn--base fs-18">@lang('Browse All') <span class="icon"><i
                        class="las la-arrow-right"></i></span></a>
        </div>
        <div class="row gy-4 justify-content-center">
            @foreach ($blogElement as $blog)
                @include($activeTemplate . '.partials.blog_content')
            @endforeach
        </div>
    </div>
</section>
