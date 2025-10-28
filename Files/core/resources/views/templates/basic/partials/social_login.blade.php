@props(['isRegisterPage' => false])

@if (!$isRegisterPage)
    @if (gs('socialite_credentials')->linkedin->status || gs('socialite_credentials')->facebook->status == Status::ENABLE || gs('socialite_credentials')->google->status == Status::ENABLE)
        <div class="my-4">
            <div class="account-form__divide text-center"><span class="text">@lang('or')</span></div>
        </div>
    @endif
@endif

@if (gs('socialite_credentials')->linkedin->status || gs('socialite_credentials')->facebook->status == Status::ENABLE || gs('socialite_credentials')->google->status == Status::ENABLE)
    <div class="d-flex justify-content-center flex-wrap gap-3 align-items-center">
        @if (gs('socialite_credentials')->facebook->status == Status::ENABLE)
            <div class="flex-grow-1">
                <a href="{{ route('user.social.login', 'facebook') }}" class="btn btn--form btn-outline--dark w-100">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <span class="icon"><img src="{{ asset($activeTemplateTrue . 'images/facebook.svg') }}" alt=""></span>
                        <span class="fs-16">@lang('Facebook')</span>
                    </div>
                </a>
            </div>
        @endif

        @if (gs('socialite_credentials')->google->status == Status::ENABLE)
            <div class="flex-grow-1">
                <a href="{{ route('user.social.login', 'google') }}" class="btn btn--form btn-outline--dark w-100">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <span class="icon"><img src="{{ asset($activeTemplateTrue . 'images/google.png') }}" alt=""></span>
                        <span class="fs-16">@lang('Google')</span>
                    </div>
                </a>
            </div>
        @endif

        @if (gs('socialite_credentials')->linkedin->status == Status::ENABLE)
            <div class="flex-grow-1">
                <a href="{{ route('user.social.login', 'linkedin') }}" class="btn btn--form btn-outline--dark w-100">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <span class="icon"><img src="{{ asset($activeTemplateTrue . 'images/linkdin.svg') }}" alt=""></span>
                        <span class="fs-16">@lang('Linkedin')</span>
                    </div>
                </a>
            </div>
        @endif
    </div>
@endif

@if ($isRegisterPage)
    @if (gs('socialite_credentials')->linkedin->status || gs('socialite_credentials')->facebook->status == Status::ENABLE || gs('socialite_credentials')->google->status == Status::ENABLE)
        <div class="my-4">
            <div class="account-form__divide text-center"><span class="text">@lang('or')</span></div>
        </div>
    @endif
@endif
