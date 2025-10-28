@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="blog-detials my-60">
        <div class="container custom-container">
            <div class="row g-5 mb-60">
                <div class="col-xl-4 col-lg-5">
                    <div class="blog-info">
                        <h2 class="blog-info__title">{{ __($blog->data_values->title) }}</h2>
                        <span class="blog-info__date"><span class="text">@lang('Published on')</span>
                            {{ showDateTime($blog->created_at, 'j F, Y') }}</span>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-7">
                    <div class="blog-banner">
                        <div class="blog-banner__image">
                            <img src="{{ frontendImage('blog', $blog->data_values->image, '870x450') }}" class="fit-image" alt="blog-banner">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5">
                <div class="col-xl-8 col-lg-7 order-lg-1">
                    <div class="blog-content">
                        <div class="text">@php echo $blog->data_values->description @endphp</div>
                        <div class="fb-comments mt-4" data-href="{{ url()->current() }}" data-numposts="5"></div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 order-lg-0">
                    <div class="blog-sidebar">
                        <hr class="blog-sidebar__divide">
                        <form action="{{ route('subscribe') }}" class="subscribeForm newsletter-form" method="post">
                            @csrf
                            <div class="subscribe-form">
                                <h6 class="mb-0">@lang('Subscribe to newsletter')</h6>
                                <input type="email" name="email" class="form--control form-control" placeholder="@lang('Enter Your Email')" required>
                                <button type="submit" class="btn btn--base w-100">@lang('Subscribe')</button>
                            </div>
                        </form>
                        <hr class="blog-sidebar__divide">
                        <div class="blog-share">
                            <h6 class="title">@lang('Share this post')</h6>
                            <div class="social-share">
                                <ul class="flex-align">
                                    <li class="social-share__item">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="social-share__link"><i class="lab la-facebook-f"></i></a>
                                    </li>
                                    <li class="social-share__item">
                                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->data_values->title) }}" target="_blank" class="social-share__link"><i class="lab la-twitter"></i></a>
                                    </li>
                                    <li class="social-share__item">
                                        <a href="http://www.linkedin.com/shareArticlemini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($blog->data_values->title) }}" target="_blank" class="social-share__link"><i class="lab la-linkedin-in"></i></a>
                                    </li>
                                    <li class="social-share__item">
                                        <a href="https://www.pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&media={{ urlencode(frontendImage('blog', $blog->data_values->image)) }}&description={{ urlencode($blog->data_values->title) }}" target="_blank" class="social-share__link"><i class="lab la-pinterest"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog my-60">
        <div class="container custom-container">
            <div class="section-heading style-left flex-between align-items-end gap-2">
                <div>
                    <h2 class="section-heading__title">@lang('Recent Blogs')</h2>
                </div>
                <a href="{{ route('blog') }}" class="btn--md btn btn--base fs-18">@lang('Browse All') <span class="icon"><i class="las la-arrow-right"></i></span></a>
            </div>
            <div class="row gy-4 justify-content-center">
                @foreach ($recentBlogs as $blog)
                    @include($activeTemplate . '.partials.blog_content')
                @endforeach
            </div>
        </div>
    </section>
@endsection

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush
