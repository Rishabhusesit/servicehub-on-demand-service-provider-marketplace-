@extends($activeTemplate . 'layouts.app')
@section('panel')
    @php
        $loginContent = getContent('login.content', true);
    @endphp


    <header class="acccount-header">
        <div class="container custom-container">
            <div class="flex-between gap-2">
                <a href="{{ route('home') }}" class="acccount-header__logo">
                    <img src="{{ siteLogo('dark') }}" alt="@lang('logo')">
                </a>
                <div class="acccount-header__content">
                    <span class="acccount-header__title"> <a href="{{ route('provider.login') }}" class="acccount-header__link">@lang('Login as a Provider')</a></span>
                </div>
            </div>
        </div>
    </header>

    <section class="account mb-60">
        <div class="container custom-container">
            <div class="login-wrapper">
                <div class="account-form">
                    <div class="mb-4 text-center">
                        <h3 class="account-form__title mb-2">{{ __($loginContent?->data_values?->heading) }}</h3>
                        <strong>{{ __($loginContent?->data_values?->subheading) }}</strong>
                    </div>
                    <form action="{{ route('user.login') }}" method="POST" class="verify-gcaptcha">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form--control" name="username" placeholder="@lang('Username or Email')">
                        </div>
                        <div class="form-group">
                            <div class="position-relative">
                                <input type="password" class="form-control form--control" placeholder="@lang('Password')" name="password">
                                <span class="password-show-hide fas fa-eye toggle-password fa-eye-slash" id="#your-password"></span>
                            </div>
                        </div>

                        <x-captcha :isFrontend="true" />

                        <div class="flex-between form-group">
                            <div class="form--check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember"> @lang('Remember Me')</label>
                            </div>
                            <a class="fw-bold forgot-pass text--base" href="{{ route('user.password.request') }}">
                                @lang('Forgot your password?')
                            </a>
                        </div>

                        <button type="submit" class="btn btn--base btn--form w-100">@lang('Login')</button>

                        @include($activeTemplate . 'partials.social_login')

                        @if (gs('registration'))
                            <div class="have-account text-center mt-4">
                                <p class="have-account__text">@lang('Don\'t have an account?') <a href="{{ route('user.register') }}" class="text--base">@lang('Register Now')</a></p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
