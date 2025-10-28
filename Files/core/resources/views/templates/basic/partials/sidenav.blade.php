@if (auth()->guard('provider')->check())
    <div class="sidebar-menu">
        <div class="sidebar-menu__close">
            <span class="icon"><i class="las la-times"></i></span>
        </div>
        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item">
                <a href="{{ route('provider.dashboard') }}"
                   class="sidebar-menu-list__link {{ menuActive('provider.dashboard') }}">@lang('Dashboard')</a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('provider.service.history') }}"
                   class="sidebar-menu-list__link {{ menuActive('provider.service.*') }}">@lang('Service History')</a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('provider.work.details') }}"
                   class="sidebar-menu-list__link {{ menuActive('provider.work.details') }}">@lang('Work Details')</a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('provider.withdraw.history') }}"
                   class="sidebar-menu-list__link {{ menuActive('provider.withdraw*') }}">@lang('Withdrawal Log')</a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('provider.transactions') }}"
                   class="sidebar-menu-list__link {{ menuActive('provider.transactions') }}">@lang('Transactions')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('ticket.index') }}"
                   class="sidebar-menu-list__link  {{ menuActive('ticket.*') }}">@lang('Support Tickets')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('provider.profile.setting') }}"
                   class="sidebar-menu-list__link {{ menuActive('provider.profile.setting') }}">@lang('Profile Setting')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('provider.change.password') }}"
                   class="sidebar-menu-list__link  {{ menuActive('provider.change.password') }}">@lang('Change Password')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('provider.twofactor') }}"
                   class="sidebar-menu-list__link  {{ menuActive('provider.twofactor') }}">@lang('2FA Security')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('provider.logout') }}"
                   class="sidebar-menu-list__link  {{ menuActive('provider.logout') }}">@lang('Logout')</a>
            </li>
        </ul>
    </div>
@elseif (auth()->guard('web')->check())
    <div class="sidebar-menu">
        <div class="sidebar-menu__close">
            <span class="icon"><i class="las la-times"></i></span>
        </div>

        <a class="sidebar-logo" href="{{ route('home') }}">
            <img src="{{ siteLogo('dark') }}" alt="@lang('Logo')">
        </a>

        <ul class="sidebar-menu-list">
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.home') }}"
                   class="sidebar-menu-list__link {{ menuActive('user.home') }}">@lang('Dashboard')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.order.history') }}"
                   class="sidebar-menu-list__link {{ menuActive('user.order.history') }}">@lang('My Orders')</a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.deposit.history') }}"
                   class="sidebar-menu-list__link {{ menuActive('user.deposit.history') }}">@lang('Payment History')</a>
            </li>


            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.transactions') }}"
                   class="sidebar-menu-list__link {{ menuActive('user.transactions') }}">@lang('Transactions')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('ticket.index') }}"
                   class="sidebar-menu-list__link  {{ menuActive('ticket.*') }}">@lang('Support Tickets')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.profile.setting') }}"
                   class="sidebar-menu-list__link {{ menuActive('user.profile.setting') }}">@lang('Profile Setting')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.change.password') }}"
                   class="sidebar-menu-list__link  {{ menuActive('user.change.password') }}">@lang('Change Password')</a>
            </li>

            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.twofactor') }}"
                   class="sidebar-menu-list__link  {{ menuActive('user.twofactor') }}">@lang('2FA Security')</a>
            </li>
            <li class="sidebar-menu-list__item">
                <a href="{{ route('user.logout') }}"
                   class="sidebar-menu-list__link  {{ menuActive('user.logout') }}">@lang('Logout')</a>
            </li>
        </ul>
    </div>
@endif
