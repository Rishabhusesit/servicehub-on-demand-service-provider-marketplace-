@php
    $recentContent = getContent('recent.content', true);
    $recentlyViewedIds = session()->get('recently_viewed_services', []);
    if (!empty($recentlyViewedIds)) {
        $recentlyViewedServices = App\Models\Service::whereIn('id', $recentlyViewedIds)
            ->orderByRaw('FIELD(id, ' . implode(',', $recentlyViewedIds) . ')')
            ->limit(4)
            ->get();
    }
@endphp

@if (!empty($recentlyViewedServices))
    <div class="recent-view my-120">
        <div class="container custom-container">
            <div class="section-heading style-left flex-between">
                <h2 class="section-heading__title">{{ __($recentContent?->data_values?->heading) }}</h2>
            </div>
            <div class="service-content">
                <div class="row gy-4">
                    @foreach ($recentlyViewedServices as $service)
                        <div class="col-xsm-6 col-sm-6 col-lg-4 col-xl-3">
                            <a href="{{ route('service.details', $service->slug) }}" class="service-item">
                                <div class="service-item__image">
                                    <img src="{{ getImage(getFilePath('service') . '/' . $service->image) }}"
                                         alt="@lang('img')">
                                </div>
                                <h5 class="service-item__title">{{ __($service->name) }}</h5>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
