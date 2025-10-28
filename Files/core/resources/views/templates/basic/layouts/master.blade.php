@extends($activeTemplate . 'layouts.app')
@section('panel')
    @include($activeTemplate . 'partials.header')
    @include($activeTemplate . 'partials.breadcrumb')
    <section class="profile">
        <div class="container">
            <div class="d-flex align-items-start flex-wrap py-60">
                @include($activeTemplate . 'partials.sidenav')
                <div class="right-body">
                    <div class="dashboard-body__bar d-lg-none d-block mb-60">
                        <span class="dashboard-body__bar-icon">
                            <i class="fa-solid fa-bars-staggered"></i>
                        </span>
                    </div>
                    @yield('content')
                </div>
            </div>
        </div>
    </section>

    @include($activeTemplate . 'partials.footer')
@endsection
