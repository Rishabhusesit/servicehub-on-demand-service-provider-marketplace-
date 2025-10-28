@php
    $pages = App\Models\Page::where('tempname', $activeTemplate)->where('is_default', Status::NO)->get();
    $language = App\Models\Language::all();
    $selectedLang = $language->where('code', session('lang'))->first();
    $serviceDropdownContent = getContent('service_dropdown.content', true);
    $categories = App\Models\Category::active()->limit(14)->get();

@endphp

<header class="header" id="header">
    <div class="container custom-container">
        <nav class="navbar navbar-expand-lg navbar-light">

            <a class="navbar-brand logo" href="{{ route('home') }}"><img src="{{ siteLogo('dark') }}" alt="@lang('Logo')"></a>
            <button class="navbar-toggler header-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span id="hiddenNav"><i class="las la-bars"></i></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav nav-menu align-items-lg-center">
                    @if (gs('multi_language'))
                        <li class="nav-item d-block d-lg-none">
                            <div class="top-button d-flex flex-wrap justify-content-between align-items-center gap-3">
                                <div class="custom--dropdown">
                                    <div class="custom--dropdown__selected dropdown-list__item">
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('language') . '/' . $selectedLang?->image, getFileSize('language')) }}" alt="@lang('img')">
                                        </div>
                                        <span class="text">{{ $selectedLang?->name }}</span>
                                    </div>
                                    <ul class="dropdown-list">
                                        @foreach ($language as $lang)
                                            <li class="dropdown-list__item change-lang " data-code="en">
                                                <a href="{{ route('lang', $lang->code) }}">
                                                    <div class="thumb">
                                                        <img src="{{ getImage(getFilePath('language') . '/' . $lang->image, getFileSize('language')) }}" alt="usa">
                                                    </div>
                                                    <span class="text">{{ $lang->name }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                <ul class="login-registration-list d-flex flex-wrap align-items-center">
                                    @if (auth()->check())
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.home') }}" class="btn--md btn btn-outline--base fs-18">@lang('Dashboard')</a>
                                        </li>
                                    @elseif(auth('provider')->check())
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('provider.dashboard') }}" class="btn--md btn btn-outline--base fs-18">@lang('Dashboard')</a>
                                        </li>
                                    @else
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('user.login') }}" class="btn--md btn btn-outline--base fs-18">@lang('Login')</a>
                                        </li>
                                        <li class="login-registration-list__item">
                                            <a href="{{ route('join') }}" class="btn btn--base btn--md fs-18">@lang('Sign up')</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    @endif

                    @if (auth('provider')->check())
                        <li class="nav-item {{ menuActive('provider.find.work') }}">
                            <a class="nav-link" href="{{ route('provider.find.work') }}">@lang('Find Work')</a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="javascript:void(0);">
                            @lang('Service')
                            <span class="nav-item__icon"><i class="las la-angle-down"></i></span></a>
                        <div class="dropdown-menu mega-menu">
                            <div class="mega-menu__list">
                                <div class="mega-menu__item">
                                    <div class="mega-menu__flexcolumn">
                                        <div class="top mb-4">
                                            <h4 class="mega-menu__heading">{{ __($serviceDropdownContent?->data_values?->heading) }}</h4>
                                            <p class="mega-menu__text">{{ __($serviceDropdownContent?->data_values?->subheading) }}</p>
                                        </div>

                                        <div class="mega-menu__button">
                                            <a href="{{ route('service') }}" class="btn--md btn btn--base">@lang('Explore All Services')<span class="icon"><i class="las la-arrow-right"></i></span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mega-menu__item">
                                    <ul class="mega-menu-nav">

                                        @foreach ($categories as $category)
                                            @if ($loop->odd)
                                                <li class="mega-menu-nav__item">
                                                    <a href="{{ route('service') }}#{{ $category->slug }}" class="mega-menu-nav__link">
                                                        <span class="text">{{ __($category->name) }}</span>
                                                        <span class="icon"><i class="las la-angle-right"></i></span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach

                                    </ul>
                                </div>
                                <div class="mega-menu__item">
                                    <ul class="mega-menu-nav">
                                        @foreach ($categories as $category)
                                            @if ($loop->even)
                                                <li class="mega-menu-nav__item">
                                                    <a href="{{ route('service') }}#{{ $category->slug }}" class="mega-menu-nav__link">
                                                        <span class="text">{{ __($category->name) }}</span>
                                                        <span class="icon"><i class="las la-angle-right"></i></span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>

                    @foreach ($pages as $k => $data)
                        <li class="nav-item {{ menuActive('pages', null, $data->slug) }}">
                            <a class="nav-link" href="{{ route('pages', [$data->slug]) }}"> {{ __($data->name) }}</a>
                        </li>
                    @endforeach
                    <li class="nav-item {{ menuActive('blog*') }}"><a class="nav-link" href="{{ route('blog') }}">@lang('Blog')</a></li>
                    <li class="nav-item {{ menuActive('contact') }}"><a class="nav-link" href="{{ route('contact') }}">@lang('Contact')</a></li>
                </ul>
            </div>

            <ul class="header-right d-lg-block d-none">
                <li class="nav-item">
                    <div class="top-button d-flex flex-wrap justify-content-between align-items-center">
                        <div class="custom--dropdown">
                            <div class="custom--dropdown__selected dropdown-list__item">
                                <div class="thumb">
                                    <img src="{{ getImage(getFilePath('language') . '/' . $selectedLang?->image, getFileSize('language')) }}" alt="@lang('img')">
                                </div>
                                <span class="text">{{ $selectedLang?->name }}</span>
                            </div>
                            <ul class="dropdown-list">
                                @foreach ($language as $lang)
                                    <li class="dropdown-list__item change-lang " data-code="en">
                                        <a href="{{ route('lang', $lang->code) }}">
                                            <div class="thumb">
                                                <img src="{{ getImage(getFilePath('language') . '/' . $lang->image, getFileSize('language')) }}" alt="usa">
                                            </div>
                                            <span class="text">{{ $lang->name }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if (auth()->guard('provider')->check())
                            <div class="user-profile dropdown">
                                <div class="user-profile-info" data-bs-toggle="dropdown">
                                    <span class="user-profile-info__image flex-center">
                                        <img src="{{ getImage(getFilePath('providerProfile') . '/' . auth()->guard('provider')->user()->image, getFileSize('providerProfile'), true) }}" alt="profile-image">
                                    </span>
                                    <div class="user-profile-info__content">
                                        <h6 class="user-profile-info__name">{{ auth()->guard('provider')->user()->username }}</h6>
                                        <div class="flex-align gap-2">
                                            <p class="user-profile-info__desc">@lang('Details')</p>
                                            <button class="user-profile-dots" type="button"><i class="fas fa-angle-down"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <ul class="dropdown-menu user-profile__dropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('provider.dashboard') }}">@lang('Dashboard')<span class="icon"><i class="las la-arrow-right"></i></span></a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('provider.service.history') }}">@lang('Service History') <span class="icon"><i class="las la-list"></i></span></a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('provider.logout') }}">@lang('Logout') <span class="icon"><i class="las la-sign-out-alt"></i></span></a>
                                    </li>
                                </ul>
                            </div>
                        @elseif(auth()->check())
                            <div class="user-profile dropdown">
                                <div class="user-profile-info" data-bs-toggle="dropdown">
                                    <span class="user-profile-info__image flex-center">
                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . auth()->user()->image, getFileSize('userProfile'),true) }}" alt="profile-image">
                                    </span>
                                    <div class="user-profile-info__content">
                                        <h6 class="user-profile-info__name">{{ auth()->user()->username }}</h6>
                                        <div class="flex-align gap-2">
                                            <p class="user-profile-info__desc">@lang('Details')</p>
                                            <button class="user-profile-dots" type="button"><i class="fas fa-angle-down"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <ul class="dropdown-menu user-profile__dropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.home') }}">@lang('Dashboard')<span class="icon"><i class="las la-arrow-right"></i></span></a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.order.history') }}">@lang('Orders') <span class="icon"><i class="las la-list"></i></span></a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.logout') }}">@lang('Logout') <span class="icon"><i class="las la-sign-out-alt"></i></span></a>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <ul class="login-registration-list flex-wrap align-items-center">
                                <li class="login-registration-list__item">
                                    <a href="{{ route('user.login') }}" class="btn--md btn btn-outline--base fs-18">@lang('Login')</a>
                                </li>
                                <li class="login-registration-list__item">
                                    <a href="{{ route('join') }}" class="btn btn--base btn--md fs-18">@lang('Register')</a>
                                </li>
                            </ul>
                        @endif
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</header>
