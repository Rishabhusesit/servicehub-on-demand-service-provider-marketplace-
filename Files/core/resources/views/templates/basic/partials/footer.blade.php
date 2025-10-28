@php
    $subscribeContent = getContent('subscribe.content', true);
    $contactContent = getContent('contact_us.content', true);
    $socialElement = getContent('social_icon.element', orderById: true);
    $policyPages = getContent('policy_pages.element', false, null, true);
    $pages = App\Models\Page::where('tempname', $activeTemplate)->limit(2)->where('is_default', Status::NO)->get();
    $categories = App\Models\Category::active()->limit(10)->get();
@endphp

<footer class="footer-area">
    <div class="pb-60 pt-80">
        <div class="container">
            <div class="row justify-content-center gy-5">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                    <div class="footer-item">
                        <div class="footer-item__logo">
                            <a href="{{ route('home') }}"> <img src="{{ siteLogo('dark') }}" alt="@lang('logo')"></a>
                        </div>
                        <div class="">
                            <div class="footer-item__custom">
                                <h5 class="footer-item__title">@lang('Address'):</h5>
                                <div class="footer-contact">
                                    <div class="footer-contact__info">
                                        <span class="icon"><i class="las la-map-marked"></i> </span>
                                        <p class="text">{{ __($contactContent->data_values->contact_details) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-item__custom">
                                <h5 class="footer-item__title">@lang('Contact'):</h5>
                                <div class="footer-contact">
                                    <div class="footer-contact__info">
                                        <span class="icon"><i class="las la-phone-volume"></i> </span>
                                        <a href="tel:{{ $contactContent?->data_values?->contact_number }}" class="text">
                                            {{ $contactContent?->data_values?->contact_number }}
                                        </a>
                                    </div>
                                    <div class="footer-contact__info">
                                        <span class="icon"> <i class="las la-envelope-open"></i> </span>
                                        <a href="mailto:{{ $contactContent?->data_values?->email_address }}" class="text">
                                            {{ $contactContent?->data_values?->email_address }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Useful Links')</h5>
                        <ul class="footer-menu">
                            <li class="footer-menu__item">
                                <a class="footer-menu__link" href="{{ route('home') }}">@lang('Home')</a>
                            </li>
                            @foreach ($pages as $page)
                                <li class="footer-menu__item"><a href="{{ route('pages', $page->slug) }}" class="footer-menu__link">{{ __($page->name) }}</a></li>
                            @endforeach
                            <li class="footer-menu__item">
                                <a class="footer-menu__link" href="{{ route('blog') }}">@lang('Blog')</a>
                            </li>
                            <li class="footer-menu__item">
                                <a class="footer-menu__link" href="{{ route('contact') }}">@lang('Contact')</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Categories')</h5>
                        <ul class="footer-menu">
                            @foreach ($categories as $category)
                                @if ($loop->odd)
                                    <li class="footer-menu__item"><a
                                           href="{{ route('service') }}#{{ $category->slug }}"
                                           class="footer-menu__link">{{ __($category->name) }}</a>
                                    </li>
                                @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-5 col-sm-5 col-xs-6">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('More Links')</h5>
                        <ul class="footer-menu">
                            @foreach ($categories as $category)
                                @if ($loop->even)
                                    <li class="footer-menu__item"><a
                                           href="{{ route('service') }}#{{ $category->slug }}"
                                           class="footer-menu__link">{{ __($category->name) }}</a>
                                    </li>
                                @endif
                            @endforeach

                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-7 col-sm-7 col-12">
                    <div class="footer-item">
                        <h5 class="footer-item__title">@lang('Subscribe')</h5>
                        <ul class="footer-menu">
                            <li class="subscription-note">
                                <p>{{ __($subscribeContent?->data_values?->heading) }}</p>
                            </li>

                            <li class="footer-subscribe">
                                <div class="newsletter-form__group">
                                    <form class="subscribeForm">
                                        <div class="input-group w-100">
                                            <input type="email" name="subscribe" class="form-control form--control" placeholder="{{ __($subscribeContent?->data_values?->placeholder_text) }}" required>
                                            <button class="btn btn--base" type="submit"><i class="las la-envelope"></i></button>
                                        </div>
                                    </form>
                                </div>

                            </li>

                        </ul>

                        <ul class="social-list">
                            @foreach ($socialElement as $element)
                                <li class="social-list__item">
                                    <a href="{{ $element?->data_values?->url }}" target="_blank" class="social-list__link">@php echo $element?->data_values?->social_icon; @endphp</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-footer">
        <div class="container">
            <div class="bottom-footer__content flex-between">
                <p class="bottom-footer__text"> @lang('Copyright') &copy; {{ date('Y') }} <a
                       href="{{ route('home') }}" class="text--base">{{ __(gs()->site_name) }}</a>
                    @lang('All Right Reserved')</p>
                <ul class="bottom-footer__list">
                    @foreach ($policyPages as $policy)
                        <li>
                            <a
                               href="{{ route('policy.pages', $policy->slug) }}">{{ __($policy?->data_values?->title) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</footer>


@push('script')
    <script>
        (function($) {
            "use strict";
            $(".subscribeForm").on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var email = $(this).find('[name=subscribe]').val();

                $.ajax({
                    url: `{{ route('subscribe') }}`,
                    method: 'post',
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: email
                    },
                    success: function(response) {
                        if (response.success) {
                            form.trigger('reset');
                            form.find('button[type=submit]').attr('disabled', false);
                            notify('success', response.message);
                        } else {
                            notify('error', response.error);
                        }
                    }
                });
            });

        })(jQuery);
    </script>
@endpush
