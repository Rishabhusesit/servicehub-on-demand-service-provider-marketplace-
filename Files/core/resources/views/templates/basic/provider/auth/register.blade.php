@php
    $registerContent = getContent('provider_register.content', true);
@endphp
@extends($activeTemplate . 'layouts.app')
@section('panel')
    @if (gs('provider_registration'))
        <header class="acccount-header">
            <div class="container custom-container">
                <div class="flex-between gap-2">
                    <a href="{{ route('home') }}" class="acccount-header__logo">
                        <img src="{{ siteLogo('dark') }}" alt="@lang('logo')">
                    </a>
                    <div class="acccount-header__content">
                        <span class="acccount-header__title">
                            <a href="{{ route('user.register') }}" class="acccount-header__link">@lang('Join as a User')</a>
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <section class="account mb-60">
            <div class="container custom-container">
                <div class="signup-wrapper">
                    <div class="account-form">
                        <div class="mb-4 text-center">
                            <h3 class="account-form__title mb-2">{{ __($registerContent?->data_values?->heading) }}</h3>
                            <strong>{{ __($registerContent?->data_values?->subheading) }}</strong>
                        </div>
                        @include($activeTemplate . 'partials.provider_social_login', [
                            'isRegisterPage' => true,
                        ])

                        <form action="{{ route('provider.register') }}" method="POST" class="verify-gcaptcha disableSubmission">
                            @csrf
                            <div class="row">

                                <div class="col-6 form-group">
                                    <input type="text" class="form--control" name="firstname" placeholder="First Name" id="firstname" value="{{ old('firstname') }}" required>
                                </div>
                                <div class="col-6 form-group">
                                    <input type="text" class="form--control" name="lastname" id="lastname" placeholder="Last name" value="{{ old('lastname') }}" required>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form--control checkProvider" name="email" id="email" placeholder="@lang('Email')" value="{{ old('email') }}" required autocomplete="off">
                                </div>
                                <div class="col-12 col-md-6 form-group">
                                    <div class="position-relative">
                                        <input id="your-password" name="password" type="password" class="form-control form--control  @if (gs('secure_password')) secure-password @endif" placeholder="@lang('Password')" required>
                                        <span class="password-show-hide fas fa-eye-slash toggle-password fa-eye" id="#your-password"></span>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 form-group">
                                    <div class="position-relative">
                                        <input id="confirm-password" name="password_confirmation" type="password" class="form-control form--control" placeholder="@lang('Confirm Password')" required>
                                        <span class="password-show-hide fas fa-eye-slash toggle-password fa-eye" id="#confirm-password"></span>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <x-captcha />
                                </div>

                                @if (gs()->agree)
                                    @php
                                        $policyPages = getContent('policy_pages.element', false, null, true);
                                    @endphp
                                    <div class="col-12 form-group">
                                        <div class="form--check">
                                            <input class="form-check-input" type="checkbox" @checked(old('agree')) name="agree" id="agree" required>
                                            <div class="form-check-label">
                                                <label class="" for="agree"> @lang('Yes, I understand and agree to the')
                                                    @foreach ($policyPages as $policy)
                                                        <a href="{{ route('policy.pages', $policy->slug) }}" class="text" target="_blank">{{ __($policy?->data_values?->title) }}</a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12 form-group">
                                    <button type="submit" id="recaptcha" class="btn btn--base btn--form w-100">@lang('Join as a Provider')</button>
                                </div>

                                <div class="col-12">
                                    <div class="have-account text-center">
                                        <p class="have-account__text">@lang('Already have an account?') <a href="{{ route('provider.login') }}" class="have-account__link text--base">@lang('Login Now')</a></p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenter" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="las la-times"></i>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <h6 class="text-center mb-0">@lang('You already have an account please Login ')</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark btn--sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <a href="{{ route('provider.login') }}" class="btn btn--base btn--sm">@lang('Login')</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        @include($activeTemplate . 'partials.registration_disabled')
    @endif

@endsection
@if (gs('provider_registration'))

    @if (gs('secure_password'))
        @push('script-lib')
            <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
        @endpush
    @endif

    @push('script')
        <script>
            "use strict";
            (function($) {

                $('.checkProvider').on('focusout', function(e) {
                    var url = '{{ route('provider.checkProvider') }}';
                    var value = $(this).val();
                    var token = '{{ csrf_token() }}';

                    var data = {
                        email: value,
                        _token: token
                    }

                    $.post(url, data, function(response) {
                        if (response.data != false) {
                            $('#existModalCenter').modal('show');
                        }
                    });
                });

                $('[name=captcha]').attr('placeholder', 'Enter Code');

            })(jQuery);
        </script>
    @endpush

@endif
