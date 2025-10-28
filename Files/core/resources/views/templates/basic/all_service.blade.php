@php
    $serviceDropdownContent = getContent('service_dropdown.content', true);
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')

    <section class="allservice">
        <div class="container custom-container">
            <div class="section-heading py-60 style-left flex-between align-items-end">
                <div class="flex-grow-1">
                    <h2 class="section-heading__title">{{ __($serviceDropdownContent?->data_values?->heading) }}</h2>
                    <h6 class="section-heading__desc">{{ __($serviceDropdownContent?->data_values?->subheading) }}</h6>
                </div>
                <form class="search-inner">
                    <input type="text" class="form--control form-control" name="search" placeholder="@lang('Find Your Service')"
                           value="{{ request()->search }}">
                    <button type="submit" class="search-icon"><i class="las la-search"></i></button>
                </form>
            </div>

            @if ($categories->count() > 0)
                <div class="d-flex align-items-start flex-wrap pb-120">
                    <div class="sidebar-menu">
                        <div class="sidebar-menu__close">
                            <span class="icon"><i class="las la-times"></i></span>
                        </div>
                        <ul class="sidebar-menu-list" id="service-scroll">
                            @foreach ($categories as $category)
                                <li class="sidebar-menu-list__item">
                                    <a href="#{{ $category->slug }}" class="sidebar-menu-list__link">
                                        {{ __($category->name) }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="right-body">
                        <div class="dashboard-body__bar d-lg-none d-block mb-60">
                            <span class="dashboard-body__bar-icon">
                                <i class="fa-solid fa-bars-staggered"></i>
                            </span>
                        </div>
                        <div data-bs-spy="scroll" data-bs-target="#service-scroll" data-bs-smooth-scroll="true">
                            @foreach ($categories as $category)
                                <div class="all-service-wrapper mb-60" id="{{ $category->slug }}">
                                    <h4 class="service-content__heading">{{ __($category->name) }}</h4>
                                    <div class="row gy-4 gy-md-5">
                                        @foreach ($category->services as $service)
                                            <div class="col-xsm-6 col-sm-6 col-lg-4 col-xl-4">
                                                <a href="{{ route('service.details', ['slug' => $service->slug]) }}"
                                                   class="service-item">
                                                    <div class="service-item__image">
                                                        <img src="{{ getImage(getFilePath('service') . '/' . $service->image) }}"
                                                             alt="@lang('image')">
                                                    </div>
                                                    <h5 class="service-item__title">{{ __($service->name) }}
                                                        @if ($service?->offers?->first())
                                                            <span class="text--danger fs-16 d-block">{{ formatOfferDiscount($service?->offers?->first()) }}</span>
                                                        @endif
                                                    </h5>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <x-empty-message />
            @endif


        </div>
    </section>
@endsection
