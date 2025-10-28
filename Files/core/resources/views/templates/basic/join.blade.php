@php
    $joinContent = getContent('join_the_community.content', true);
@endphp
@extends($activeTemplate . 'layouts.app')
@section('panel')
    <header class="acccount-header">
        <div class="container custom-container">
            <div class="flex-between gap-2">
                <a href="{{ route('home') }}" class="acccount-header__logo">
                    <img src="{{ siteLogo('dark') }}" alt="@lang('Logo')">
                </a>
            </div>
        </div>
    </header>
    <div class="signup">
        <div class="container custom-container">
            <div class="signup-wrapper">
                <div class="account-form">
                    <h3 class="account-form__title">
                        {{ __($joinContent?->data_values?->heading) }}
                    </h3>
                    <form action="#">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="signstep-item active">
                                    <div class="signstep-item__check">
                                        <input class="signstep-item__check-input radioTwo" type="radio" name="signup"
                                               checked data-route="{{ route('user.register') }}"
                                               data-route-login="{{ route('user.login') }}">
                                        <label></label>
                                    </div>
                                    <div class="signstep-item__icon">
                                        <img src="{{ frontendImage('join_the_community', $joinContent?->data_values?->client_icon, '64x64') }}"
                                             alt="client-icon">
                                    </div>
                                    <h6 class="signstep-item__title">
                                        {{ __($joinContent?->data_values?->client_text) }}
                                    </h6>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="signstep-item">
                                    <div class="signstep-item__check">
                                        <input class="signstep-item__check-input radioOne" type="radio" name="signup"
                                               data-route="{{ route('provider.register') }}"
                                               data-route-login="{{ route('provider.login') }}">
                                        <label></label>
                                    </div>
                                    <div class="signstep-item__icon">
                                        <img src="{{ frontendImage('join_the_community', $joinContent?->data_values?->professional_icon, '64x64') }}"
                                             alt="professional-icon">
                                    </div>
                                    <h6 class="signstep-item__title">
                                        {{ __($joinContent?->data_values?->professional_text) }}
                                    </h6>
                                </div>
                            </div>
                            <div class="col-12 form-group">
                                <a href="{{ route('user.register') }}" class="signup-btn btn btn--form btn--base w-100">
                                    @lang('Join as a User')
                                </a>
                            </div>

                            <div class="col-12">
                                <div class="have-account text-center">
                                    <p class="have-account__text">@lang('Already have an account?') <a href="{{ route('user.login') }}"
                                           class="have-account__link text--base">@lang('Login Now')</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.signstep-item').on('click', function() {
                $('.signstep-item').removeClass('active');
                $(this).addClass('active');

                const providerRegisterRoute = $(".radioOne").data('route');
                const providerLoginRoute = $(".radioOne").data('route-login');
                const userRegisterRoute = $(".radioTwo").data('route');
                const userLoginRoute = $(".radioTwo").data('route-login');

                if ($('.radioOne').is(':checked')) {
                    $('.signup-btn').text("@lang('Join as a Provider')");
                    $('.signup-btn').attr('href', providerRegisterRoute);
                    $('.have-account__link').attr('href', providerLoginRoute);
                } else if ($('.radioTwo').is(':checked')) {
                    $('.signup-btn').text("@lang('Join as a User')");
                    $('.signup-btn').attr('href', userRegisterRoute);
                    $('.have-account__link').attr('href', userLoginRoute);
                }
            });
        })(jQuery);
    </script>
@endpush
