@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include($activeTemplate . 'partials.header')
    <main>
        @if (!request()->routeIs('home', 'service.*'))
            @include($activeTemplate . 'partials.breadcrumb')
        @endif
        @yield('content')
    </main>
    @include($activeTemplate . 'partials.footer')

    @php
        $cookie = App\Models\Frontend::where('data_keys', 'cookie.data')->first();
    @endphp

    @if ($cookie->data_values->status == Status::ENABLE && !\Cookie::get('gdpr_cookie'))
        <div class="cookies-card hide">
            <div class="cookies-card__icon">
                <i class="las la-cookie-bite"></i>
            </div>
            <p class="mt-3 cookies-card__content">{{ $cookie?->data_values?->short_desc }} <a href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a></p>
            <div class="cookies-card__btn mt-3">
                <a href="javascript:void(0)" class="btn btn--base btn--sm policy">@lang('Allow')</a>
                <a href="javascript:void(0)" class="btn btn-outline--base btn--sm policy">@lang('Reject')</a>
            </div>
        </div>
    @endif
@endsection
