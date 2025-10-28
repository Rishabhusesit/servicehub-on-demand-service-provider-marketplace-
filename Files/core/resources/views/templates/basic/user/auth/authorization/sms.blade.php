@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <div class="py-120">
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="verification-code-wrapper custom--card">
                    <div class="verification-area">
                        <h5 class="pb-3 text-center border-bottom">@lang('Verify Mobile Number')</h5>
                        <form action="{{ route('user.verify.mobile') }}" method="POST" class="submit-form">
                            @csrf
                            <p class="verification-text">@lang('A 6 digit verification code sent to your mobile number') :
                                +{{ showMobileNumber(auth()->user()->mobileNumber) }}</p>
                            @include($activeTemplate . 'partials.verification_code')
                            <div class="mb-3">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                            <div class="form-group">
                                <p class="d-flex flex-wrap gx-2">
                                    @lang('If you don\'t get any code'), <span class="countdown-wrapper">@lang('try again after') <span
                                              id="countdown" class="fw-bold">--</span> @lang('seconds')</span> <a
                                       href="{{ route('user.send.verify.code', 'sms') }}" class="try-again-link d-none">
                                        @lang('Try again')</a>
                                </p>
                                <a href="{{ route('user.logout') }}">@lang('Logout')</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        "use strict";
        var distance = Number("{{ isset($user->ver_code_send_at) ? $user->ver_code_send_at->addMinutes(2)->timestamp - time() : '' }}");
        var x = setInterval(function() {
            distance--;
            document.getElementById("countdown").innerHTML = distance;
            if (distance <= 0) {
                clearInterval(x);
                document.querySelector('.countdown-wrapper').classList.add('d-none');
                document.querySelector('.try-again-link').classList.remove('d-none');
            }
        }, 1000);
    </script>
@endpush

@push('style')
    <style>
        .verification-code span {
            background: transparent;
            border: solid 1px #{{ gs('base_color') }}4d !important;
            color: #{{ gs('base_color') }} !important;
        }
    </style>
@endpush
